import Authenticated from "@/Layouts/AuthenticatedLayout";
import { Authorization, User } from "@/types";
import { Anchor, Card } from "@adan-sohersa/dummy-design-system";

import { usePage } from "@inertiajs/react";

interface AllAuthorizationsPageProps {
	authorizations: Authorization[],
	type: string,
	id: string,
	user: User,
	apsAuthorizationUrl: string
}

const modules = [
	{
		icon: 'https://bim-takeoff-landing.web.app/logos/calculator.ico',
		name: 'TakeOff',
		subdomainWtDoth: 'takeoff.'
	}
]

export default function AllAuthorizationsPage(props: AllAuthorizationsPageProps) {

	const { type, id, authorizations, apsAuthorizationUrl, ...rest } = props;

	const { ecosystem } = usePage().props;

	return (<Authenticated {...rest}>
		<>
			<h1>Autorizations Page</h1>

			<section className="grid grid-cols-4 gap-4">

				{authorizations.map(authorization => (
					<Card key={authorization.id}>
						<>
							<img
								className="w-1/4 rounded-full mx-auto"
								src={authorization.user_picture}
								alt={`Picture of ${authorization.username_at_provider} at ${authorization.provider}`}
							/>
							<p className="font-semibold text-large text-center">{authorization.username_at_provider}</p>
							<p className="font-base text-base text-center">{authorization.provider}</p>
							<p className="font-light text-small text-center">{authorization.scopes}</p>

							<span className="grid grid-cols-5 gap-1">
								{
									modules.map(module =>
										<Anchor
											className="capitalize"
											href={`${module.subdomainWtDoth + (ecosystem as any).domain}?authorization=${authorization.id}`}
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
										</Anchor>
									)
								}
							</span>
						</>
					</Card>
				))}

				<Card>
					<div className="flex flex-col justify-center items-center">
						<Anchor className="text-center text-sm" href={apsAuthorizationUrl}>New Autodesk Authorization</Anchor>
					</div>
				</Card>

			</section>

		</>
	</Authenticated>)
}
