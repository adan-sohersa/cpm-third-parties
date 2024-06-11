import { ButtonColors, ButtonProps } from "@adan-sohersa/dummy-design-system";

/**
 * Calls the internal endpoind for signing the user out.
 * Then, redirects the user to the signin page from the Authenticator App.
 */
const signOut = async () => {
	const response = await fetch('/api/auth/logout', {
		method: 'GET'
	});

	if (response.ok) {
		location.href = import.meta.env.VITE_AUTHENTICATOR_SIGNIN_URL ?? '/';
	}

	throw new Error('Error while logging out');
}

/**
 * The list of actions that can be performed on the user.
 */
const authActions: ButtonProps[] = [
	{
		onClick: signOut,
		children: 'Sign out',
		color: ButtonColors.danger,
		// @ts-ignore
		variant: 'light',
		key: 'signOut'
	}
];

export { authActions };