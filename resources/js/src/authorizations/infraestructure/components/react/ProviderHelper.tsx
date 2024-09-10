import { Anchor } from "@adan-sohersa/dummy-design-system";

interface ProviderHelperProps {
	providerName: string
}

const ProviderHelper: (props: ProviderHelperProps) => JSX.Element = (props) => {

	const { providerName } = props;

	if (providerName === 'ACC') {
		return (<article className="flex flex-col gap-4">
			<p>In order to access your data from Autodesk Construction Cloud, it is required to:</p>
			<ul className="list-disc pl-4">
				<li className="mb-8">Provide an user level authorization by:
					<ul className="list-[circle] pl-4">
						<li className="my-2">Click the <b>New Authorization</b> button.</li>
						<img src="/images/authorizations/ss_authorizations.webp" alt="Button for navigate to the Autodesk Authorization Workflow." />
						<li className="my-2">Allow the access to your data.</li>
						<img className="h-80 mx-auto" src="/images/authorizations/ss_autodesk_authorization.webp" alt="Button for allowing the access to the ACC data." />
					</ul>
				</li>
				<li className="mb-8">
					Provide an organization level authorization:
					<ul className="list-[circle] pl-4">
						<li className="my-2">Open your <Anchor className="text-base" href="https://acc.autodesk.com/projects">ACC Projects dashboard</Anchor>.</li>
						<li className="my-2">Click the <span className="text-secondary">Account Admin</span> link.</li>
						<img src="/images/authorizations/ss_autodesk_projects.webp" alt="Screen shot showing where the Account Admin Link is." />
						<li className="my-2">Click the <span className="text-secondary">account picker</span> an choose the account you want to authorize.</li>
						<img src="/images/authorizations/ss_pick_account_in_account_admin.webp" alt="Screeh shot showing where the account picker is." />
						<li className="my-2">Open the <span className="text-secondary">Custom Integrations</span> section. Then, click the <span className="text-secondary">+ Add Custom Integration</span> button.</li>
						<img src="/images/authorizations/ss_autodesk_custom_integrations_dashboard.webp" alt="Screen shot showing where the Add Custom Integration button is." />
						<li className="my-2">In the modal that appears, set <span className="text-secondary break-all">{import.meta.env.VITE_AUTODESK_CLIENT_ID}</span> in the client id field and <span className="text-secondary break-all">{import.meta.env.VITE_AUTODESK_CLIENT_ALIAS}</span> as the integration name field. Finally, click the <span className="text-secondary">Add</span> button.</li>
					</ul>
				</li>
			</ul>
		</article>)
	}

	return (<></>)
}

export { type ProviderHelperProps, ProviderHelper }
