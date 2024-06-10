import './bootstrap';
import '../css/app.css';

import { createRoot } from 'react-dom/client';
import { createInertiaApp } from '@inertiajs/react';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { DSProvider } from '@adan-sohersa/dummy-design-system';

const appName = import.meta.env.VITE_APP_NAME || 'Nimbus Sphere';

createInertiaApp({
	title: (title) => `${title} | ${appName}`,
	resolve: (name) => resolvePageComponent(`./Pages/${name}.tsx`, import.meta.glob('./Pages/**/*.tsx')),
	setup({ el, App, props }) {
		const root = createRoot(el);

		root.render(
			<DSProvider>
				<App {...props} />
			</DSProvider>
		);
	},
	progress: {
		color: '#4B5563',
	},
});
