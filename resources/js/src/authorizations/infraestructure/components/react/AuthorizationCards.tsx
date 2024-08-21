import { Authorization, EcosystemModule } from "@/types";
import { AuthorizationCard } from "./AuthorizationCard";

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
		<>
			{
				authorizations.map(authorization => (
					<AuthorizationCard key={authorization.id} authorization={authorization} modules={modules} />
				))
			}
		</>
	)

}

export { type AuthorizationCardsProps, AuthorizationCards }