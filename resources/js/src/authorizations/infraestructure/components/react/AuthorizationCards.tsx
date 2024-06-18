import { Authorization, EcosystemModule } from "@/types";
import { Anchor } from "@adan-sohersa/dummy-design-system";

interface AuthorizationCardsProps {
	authorizations?: Authorization[],
	modules: EcosystemModule[]
}

const AuthorizationCards: (props: AuthorizationCardsProps) => JSX.Element = (props: AuthorizationCardsProps) => {
	const { authorizations, modules } = props;

	if (typeof authorizations === 'undefined') {
		return (
			<div className="w-full h-full flex items-center justify-center">
				<p className="text-center">No authorizations for this provider yet.</p>
			</div>
		)
	}

	return (
		<div>
			{
				authorizations.map(authorization => (
					<article key={authorization.id} className="pb-6 last-of-type:pb-2">

						<div className="w-full flex gap-2">

							<img className="h-14 w-14 rounded-full object-cover" src={authorization.pictureAtProvider} alt={`Picture of ${authorization.usernameAtProvider} in ${authorization.provider}`} />

							<div>
								<p className="font-base text-large">{authorization.usernameAtProvider}</p>
								<p className="font-light text-small">{authorization.scopes}</p>
								<p className="font-light text-small flex gap-1">Status:
									<span className={`text-${authorization.active ? 'success' : 'danger'}`}>
										{authorization.active ? 'Active' : 'Inactive'}
									</span>
								</p>
							</div>

						</div>

						<div className="grid grid-cols-5 gap-1 py-2">
							{
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
				))
			}
		</div>
	)

}

export { type AuthorizationCardsProps, AuthorizationCards }