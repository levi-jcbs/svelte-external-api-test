import type { PageLoad } from './$types';

export const load: PageLoad = async ({ fetch }) => {
	const res = await fetch('/api/');
	const apiresponse = await res.json();

	return {
		status: apiresponse.status
	};
};