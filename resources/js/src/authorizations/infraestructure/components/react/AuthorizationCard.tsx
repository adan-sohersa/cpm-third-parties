import { refreshToken } from "@/src/authorizations/application/refresh-authorization";
import { RefreshCircleSharp } from "@/src/ui/infraestructure/components/react/RefreshCircleSharp";
import { Authorization, EcosystemModule } from "@/types";
import { Anchor, Button, ButtonSize, ButtonVariants } from "@adan-sohersa/dummy-design-system";
import { HTMLAttributes, useState } from "react";

interface AuthorizationCardProps extends HTMLAttributes<HTMLElement> {
	authorization: Authorization,
	modules: EcosystemModule[]
}

const AuthorizationCard: (props: AuthorizationCardProps) => JSX.Element = (props: AuthorizationCardProps) => {
	const { authorization: initialAuthorization, modules } = props;

	const [isRefreshing, setIsRefreshing] = useState(false);
	const [authorization, setAuthorization] = useState(initialAuthorization);

	const handleAuthorizationRefresh = async () => {
		setIsRefreshing(true);

		try {
			const refreshedAuthorization = await refreshToken(authorization);
			setAuthorization(refreshedAuthorization);
		} catch (error) {
			console.error(error); // @debug
		} finally {
			setIsRefreshing(false);
		}
	}

	return (
		<article className="pb-6 last-of-type:pb-2">

			<div className="w-full flex gap-2">

				<img className="h-14 w-14 rounded-full object-cover" src={authorization.pictureAtProvider} alt={`Picture of ${authorization.usernameAtProvider} in ${authorization.provider}`} />

				<div>
					<p className="font-base text-large">{authorization.usernameAtProvider}</p>
					<p className="font-light text-small">{authorization.scopes}</p>
					<div className="font-light text-small flex items-center gap-2">

						<p className="flex gap-1.5">
							Status:
							<span className={`text-${authorization.active ? 'success' : 'danger'}`}>
								{authorization.active ? 'Active' : 'Inactive'}
							</span>
						</p>

						<Button
							disabled={isRefreshing}
							onClick={() => handleAuthorizationRefresh()}
							size={ButtonSize.small}
							variant={ButtonVariants.light}
							className="min-w-min h-fit p-1">
							<RefreshCircleSharp className="w-6 h-6" />
						</Button>

					</div>
				</div>

			</div>

			<div className="grid grid-cols-5 gap-1 py-2">
				{
					authorization.active &&
					modules.map(module => {

						const redirectionUrl = module.redirectionTemplate
							.replace('{authorizationId}', authorization.id)
							.replace('{domain}', import.meta.env.VITE_ECOSYSTEM_DOMAIN);

						return (<Anchor
							className="capitalize"
							href={redirectionUrl}
							target="_blank"
							key={module.name}
						>
							<span className="flex items-center gap-1.5">
								<img
									className="w-5 h-5 rounded-full"
									src={module.icon}
								/>
								{module.name}
							</span>
						</Anchor>)
					})
				}
			</div>


		</article>
	)
}

export { type AuthorizationCardProps, AuthorizationCard }