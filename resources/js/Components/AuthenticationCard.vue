<script setup>
import SpinBeforeLoaded from '@/Components/Common/SpinBeforeLoaded.vue'
import Notyf from '@/Components/Common/Notyf.vue'
</script>
<template>
    <SpinBeforeLoaded />
    <Notyf :data="$page.props.sessionAlert" />
    <slot name="notyf" />
    <div
        class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100"
    >
        <div>
            <slot name="logo" />
        </div>

        <div
            class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg"
        >
            <slot />
        </div>
    </div>
</template>
<style lang="scss">
@import './../../sass/app.scss';
</style>
