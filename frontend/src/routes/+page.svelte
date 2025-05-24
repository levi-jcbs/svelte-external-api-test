<script lang="ts">
	let { data } = $props();

	const apicall_setcookie = () => {
		fetch("/api/setcookie", { method: "POST" }).then((response) => {
			if (response.status === 200) {
				alert("Cookie set successfully!");
			} else {
				alert("Failed to set cookie.");
			}
		});
	};
</script>

<h1>Welcome to SvelteKit</h1>
<p>Visit <a href="https://svelte.dev/docs/kit">svelte.dev/docs/kit</a> to read the documentation</p>

<button onclick={apicall_setcookie}>Set cookie</button>

<p>
	API Answer:
	{data.status}
</p>
