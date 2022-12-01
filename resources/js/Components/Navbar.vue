<script setup>
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';

const props = defineProps([
    'login'
])


</script>


<template>
    <nav class="navbar navbar-expand-lg bg-light">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div v-if="props.login">
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <ResponsiveNavLink :href="route('index')" method="get" as="button">
                                Home
                            </ResponsiveNavLink>
                        </li>
                        <li class="nav-item">
                            <ResponsiveNavLink :href="route('logout')" method="post" as="button">
                                Log Out
                            </ResponsiveNavLink>
                        </li>

                        <li class="nav-item">
                            <ResponsiveNavLink :href="route('allAnswers')" method="get" as="button">
                                Solved quiz
                            </ResponsiveNavLink>
                        </li>
                    </ul>
                </div>
            </div>

            <div v-else>
                <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                            <ResponsiveNavLink :href="route('index')" method="get" as="button">
                                Home
                            </ResponsiveNavLink>
                        </li>
                    <li class="nav-item">
                        <ResponsiveNavLink :href="route('register')" method="get" as="button">
                            Register
                        </ResponsiveNavLink>
                    </li>
                    <li class="nav-item">
                        <ResponsiveNavLink :href="route('login')" method="get" as="button">
                            Login
                        </ResponsiveNavLink>
                    </li>
                </ul>
            </div>
            </div>

        </div>
    </nav>
</template>
