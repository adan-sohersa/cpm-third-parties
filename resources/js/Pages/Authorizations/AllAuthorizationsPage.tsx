import Authenticated from "@/Layouts/AuthenticatedLayout";
import { Authorization, User } from "@/types";
import { Anchor, ButtonVariants, Card, Modal } from "@adan-sohersa/dummy-design-system";

import { Head } from "@inertiajs/react";

interface AllAuthorizationsPageProps {
	authorizations: Authorization[],
	type: string,
	id: string,
	user: User,
	providersWithAuthorizationURL: { [key: string]: string }
}

const modules = [
	{
		icon: 'https://bim-takeoff-landing.web.app/logos/calculator.ico',
		name: 'TakeOff',
		redirectionTemplate: 'http://takeoff.{domain}/app/autodesk/viewer?authorization={authorizationId}'
	}
]

const providerHelpers: { [key: string]: JSX.Element } = {
	ACC: (<article className="flex flex-col gap-4">
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

export default function AllAuthorizationsPage(props: AllAuthorizationsPageProps) {

	const { type, id, authorizations, providersWithAuthorizationURL, ...rest } = props;

	const groupedAuthorizations = Object.groupBy(authorizations, authorization => authorization.provider);

	// console.log(providersWithAuthorizationURL); // @debug

	// console.log(groupedAuthorizations); // @debug

	return (<Authenticated header={'Authorizations'} {...rest}>
		<main className="lg:px-[calc(50%-610px)]">
			<Head>
				<title>Authorizations</title>
				<meta name="description" content="Grant access to third party resources." />
			</Head>

			<section className="grid gri-cols-2 sm:grid-cols-3 xl:grid-cols-3 gap-4">

				{
					// Getting the providers from the providersWithAuthorizationURL
					Object.keys(providersWithAuthorizationURL).map(provider => {
						// Getting the providerAuthorizations for the provider
						const providerAuthorizations = groupedAuthorizations[provider];
						// Getting the authorization url for the provider
						const providerAuthorizationUrl = providersWithAuthorizationURL[provider];

						if (typeof providerAuthorizations === 'undefined') {
							// Building an providerAuthorizations card for the provider
							return (<Card key={provider} className="min-h-80">
								<div className="flex items-center justify-between border-b border-neutral-300 dark:border-neutral-700 border-solid py-2">
									<h2 className="text-2xl font-semibold">{provider}</h2>

									<span className="flex items-center">
										<Anchor href={providerAuthorizationUrl} target="_blank">New Authorization</Anchor>
										<Modal buttonVariant={ButtonVariants.light}
											title={`Authorization for ${provider}`}
											buttonChildren={<svg xmlns="http://www.w3.org/2000/svg" width="512" height="512" viewBox="0 0 512 512">
												<path fill="#dfdfdf" d="M256,64C150,64,64,150,64,256s86,192,192,192,192-86,192-192S362,64,256,64Zm-6,304a20,20,0,1,1,20-20A20,20,0,0,1,250,368Zm33.44-102C267.23,276.88,265,286.85,265,296a14,14,0,0,1-28,0c0-21.91,10.08-39.33,30.82-53.26C287.1,229.8,298,221.6,298,203.57c0-12.26-7-21.57-21.49-28.46-3.41-1.62-11-3.2-20.34-3.09-11.72.15-20.82,2.95-27.83,8.59C215.12,191.25,214,202.83,214,203a14,14,0,1,1-28-1.35c.11-2.43,1.8-24.32,24.77-42.8,11.91-9.58,27.06-14.56,45-14.78,12.7-.15,24.63,2,32.72,5.82C312.7,161.34,326,180.43,326,203.57,326,237.4,303.39,252.59,283.44,266Z" />
											</svg>}>
											{providerHelpers[provider]}
										</Modal>
									</span>
								</div>
								<div className="w-full h-full flex items-center justify-center">
									<p className="text-center">No authorizations for this provider yet.</p>
								</div>
							</Card>)
						}

						// Building an providerAuthorizations card for the provider
						return (<Card key={provider} className="min-h-80">

							<div className="flex items-center justify-between border-b border-neutral-300 dark:border-neutral-700 border-solid py-2">
								<h2 className="text-2xl font-semibold">{provider}</h2>

								<span className="flex items-center">
									<Anchor href={providerAuthorizationUrl} target="_blank">New Authorization</Anchor>
									<Modal buttonVariant={ButtonVariants.light}
										title={`Authorization for ${provider}`}
										buttonChildren={<svg xmlns="http://www.w3.org/2000/svg" width="512" height="512" viewBox="0 0 512 512">
											<path fill="#dfdfdf" d="M256,64C150,64,64,150,64,256s86,192,192,192,192-86,192-192S362,64,256,64Zm-6,304a20,20,0,1,1,20-20A20,20,0,0,1,250,368Zm33.44-102C267.23,276.88,265,286.85,265,296a14,14,0,0,1-28,0c0-21.91,10.08-39.33,30.82-53.26C287.1,229.8,298,221.6,298,203.57c0-12.26-7-21.57-21.49-28.46-3.41-1.62-11-3.2-20.34-3.09-11.72.15-20.82,2.95-27.83,8.59C215.12,191.25,214,202.83,214,203a14,14,0,1,1-28-1.35c.11-2.43,1.8-24.32,24.77-42.8,11.91-9.58,27.06-14.56,45-14.78,12.7-.15,24.63,2,32.72,5.82C312.7,161.34,326,180.43,326,203.57,326,237.4,303.39,252.59,283.44,266Z" />
										</svg>}>
										{providerHelpers[provider]}
									</Modal>
								</span>
							</div>

							<div className="w-full pt-4">
								{
									providerAuthorizations.map(authorization => (
										<article key={authorization.id} className="last-of-type:border-b-0 border-b border-solid">

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
						</Card>)
					})
				}

			</section>
		</main>
	</Authenticated >)
}
