import Authenticated from "@/Layouts/AuthenticatedLayout";
import { Authorization, User } from "@/types";
import { Anchor, Card } from "@adan-sohersa/dummy-design-system";

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
		redirectionTemplate: 'http://takeoff.{domain}/autodesk/viewer?authorization={authorizationId}'
	}
]

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

			<section className="grid gri-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-4">

				{
					// Getting the providers from the grouped authorizations
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
									<Anchor href={providerAuthorizationUrl} target="_blank">New Authorization</Anchor>
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
								<Anchor href={providerAuthorizationUrl} target="_blank">New Authorization</Anchor>
							</div>

							<div className="w-full pt-4">
								{
									providerAuthorizations.map(authorization => (
										<article key={authorization.id} className="last-of-type:border-b-0 border-b border-solid">

											<div className="w-full flex gap-2">

												<img className="h-14 w-14 rounded-full object-cover" src={authorization.user_picture} alt={`Picture of ${authorization.username_at_provider} in ${authorization.provider}`} />

												<p>
													<span className="font-base text-large">{authorization.username_at_provider}</span>
													<span className="font-light text-small">{authorization.scopes}</span>
												</p>

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
