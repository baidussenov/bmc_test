<script setup>
import NewsList from '@/components/NewsList.vue'
import { capitalize } from '@/utils/helper'
</script>

<template>
    <h1 class="title">{{ capitalize($route.name) }}</h1>
    <NewsList />
</template>
