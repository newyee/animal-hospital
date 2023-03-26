<script setup lang="ts">
  import GithubIcon from 'vue-material-design-icons/Github.vue'
  import { APP_TITLE } from '@/config'
  import { User } from '@/types'
  import useAuth from '@/hooks/useAuth'
  import { useRouter } from 'vue-router'

  const { logout } = useAuth()

  const router = useRouter()

  const props = defineProps<{
    user: User | undefined
  }>()
  async function onLogout() {
    await logout()
    router.push('/login')
  }
</script>

<template>
  <div class="flex flex-col min-h-screen">
    <div
      class="pb-5 daisy-sticky top-0 z-30 flex h-16 w-full justify-center bg-opacity-90 backdrop-blur transition-all duration-100 bg-base-100 text-base-content shadow-sm"
    >
      <nav class="daisy-navbar w-full">
        <div class="daisy-navbar-start">
          <router-link
            to="/"
            class="daisy-btn daisy-btn-ghost normal-case text-xl"
            >{{ APP_TITLE }}</router-link
          >
        </div>
        <div class="daisy-navbar-end gap-2">
          <div v-if="!props.user">
            <router-link to="/login" class="daisy-btn mr-3 daisy-btn-outline"
              >Login</router-link
            >
            <router-link to="/register" class="daisy-btn daisy-btn-warning"
              >Register</router-link
            >
          </div>
          <div v-else>
            <router-link to="/mypage" class="daisy-btn mr-3 daisy-btn-outline"
              >マイページ</router-link
            >
            <button
              class="daisy-btn daisy-btn-warning"
              @click.prevent="onLogout"
            >
              ログアウト
            </button>
          </div>
        </div>
      </nav>
    </div>
    <div class="flex-1 px-6 xl:pr-2 bg-gray-100 pb-10 pt-10">
      <slot />
    </div>
    <footer
      class="flex-shrink-0 daisy-footer items-center p-4 bg-neutral text-neutral-content"
    >
      <div class="items-center grid-flow-col">
        <p>Copyright © 2023 - All right reserved</p>
      </div>
      <div class="grid-flow-col gap-4 md:place-self-center md:justify-self-end">
        <a href="https://github.com/newyee/animal-hospital" target="_blank">
          <GithubIcon />
        </a>
      </div>
    </footer>
  </div>
</template>
