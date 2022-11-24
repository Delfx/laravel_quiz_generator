<script setup>
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Head, Link } from '@inertiajs/inertia-vue3';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
})

</script>


<template>

    <div v-if="$page.props.auth.user">
        <div class="tw-pt-4 tw-pb-1 tw-border-t tw-border-gray-200">
            <div class="tw-mt-3 tw-space-y-1">
                <ResponsiveNavLink :href="route('logout')" method="post" as="button">
                    Log Out
                </ResponsiveNavLink>
            </div>
        </div>
    </div>

    <div v-else class="row">
        <ResponsiveNavLink :href="route('login')"
            class="col-md-1 tw-text-sm tw-text-gray-700 dark:tw-text-gray-500 tw-underline">Log in</ResponsiveNavLink>

        <ResponsiveNavLink :href="route('register')"
            class="col-md-1 tw-text-sm tw-text-gray-700 dark:tw-text-gray-500 tw-underline">Register</ResponsiveNavLink>
    </div>



    <h1>
        Index
    </h1>

</template>
