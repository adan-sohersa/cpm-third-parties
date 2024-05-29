import Authenticated from "@/Layouts/AuthenticatedLayout";
import { Authorization, User } from "@/types";

interface AllAuthorizationsPageProps {
	authorizations: Authorization[],
	type: string,
	id: string,
	user: User,
	apsAuthorizationUrl: string
}

export default function AllAuthorizationsPage(props: AllAuthorizationsPageProps) {

	const { type, id, authorizations, apsAuthorizationUrl, ...rest } = props;

	return (<Authenticated {...rest}>
		<>
			<h1>Autorizations Page</h1>

			{authorizations.map(authorization => (<p key={authorization.id}>{authorization.provider} - {authorization.username_at_provider} - {authorization.scopes}</p>))}

			<a href={apsAuthorizationUrl}>New Autodesk Authorization</a>

		</>
	</Authenticated>)
}
