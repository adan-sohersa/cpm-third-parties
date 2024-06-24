import Authenticated from "@/Layouts/AuthenticatedLayout";
import { AuthorizationCards } from "@/src/authorizations/infraestructure/components/react/AuthorizationCards";
import { ProviderHelper } from "@/src/authorizations/infraestructure/components/react/ProviderHelper";
import { QuestionMark } from "@/src/ui/infraestructure/components/react/QuestionMark";
import { Authorization, EcosystemModule, User } from "@/types";
import { Anchor, ButtonVariants, Card, Modal } from "@adan-sohersa/dummy-design-system";

import { Head } from "@inertiajs/react";

interface AllAuthorizationsPageProps {
	authorizations: Authorization[],
	type: string,
	id: string,
	user: User,
	providersWithAuthorizationURL: { [key: string]: string }
}

const modules: EcosystemModule[] = [
	{
		icon: 'https://bim-takeoff-landing.web.app/logos/calculator.ico',
		name: 'TakeOff',
		redirectionTemplate: 'http://takeoff.{domain}/app/autodesk/viewer?authorization={authorizationId}'
	}
]

export default function AllAuthorizationsPage(props: AllAuthorizationsPageProps) {

	const { type, id, authorizations, providersWithAuthorizationURL, ...rest } = props;

	const groupedAuthorizations = Object.groupBy(authorizations, authorization => authorization.provider);

	return (<Authenticated header={'Authorizations'} {...rest}>
		<>

			<Head>
				<title>Authorizations</title>
				<meta name="description" content="Grant access to third party resources." />
			</Head>

			<main className="lg:px-[calc(50%-610px)]">

				<section className="grid gri-cols-2 sm:grid-cols-2 lg:grid-cols-3 gap-4">

					{
						// Getting the providers from the providersWithAuthorizationURL
						Object.keys(providersWithAuthorizationURL).map(provider => {
							// Getting the providerAuthorizations for the provider
							const providerAuthorizations = groupedAuthorizations[provider];
							// Getting the authorization url for the provider
							const providerAuthorizationUrl = providersWithAuthorizationURL[provider];

							// Building an providerAuthorizations card for the provider
							return (<Card key={provider} className="min-h-80" header={
								<div className="flex items-center justify-between w-full">
									<h2 className="text-2xl font-semibold">{provider}</h2>

									<span className="flex items-center">

										<Anchor href={providerAuthorizationUrl} target="_blank">New Authorization</Anchor>

										<Modal buttonVariant={ButtonVariants.light}
											title={`Authorization for ${provider}`}
											buttonChildren={<QuestionMark />}>
											<ProviderHelper providerName={provider} />
										</Modal>

									</span>
								</div>
							}>

								<AuthorizationCards authorizations={providerAuthorizations} modules={modules} />

							</Card>)
						})
					}

				</section>

			</main>

		</>
	</Authenticated >)
}

