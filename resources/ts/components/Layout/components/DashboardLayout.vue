<script setup lang="ts">
  import { useRouter } from 'vue-router'
  import useAuth from '@/hooks/useAuth'
  import MenuIcon from 'vue-material-design-icons/Menu.vue'
  import AccountIcon from 'vue-material-design-icons/Account.vue'
  import ChevronDownIcon from 'vue-material-design-icons/ChevronDown.vue'
  import LogoutIcon from 'vue-material-design-icons/Logout.vue'
  import { APP_TITLE } from '@/config'

  const { logout } = useAuth()
  const router = useRouter()

  async function onLogout() {
    await logout()
    router.push('/login')
  }
</script>

<template>
  <div class="daisy-drawer daisy-drawer-mobile">
    <input
      id="app-drawer-sidemenu"
      type="checkbox"
      class="daisy-drawer-toggle"
    />
    <div class="daisy-drawer-content">
      <div
        class="daisy-sticky top-0 z-30 flex h-16 w-full justify-center bg-opacity-90 backdrop-blur transition-all duration-100 bg-base-100 text-base-content shadow-sm"
      >
        <nav class="daisy-navbar w-full">
          <div class="daisy-navbar-start">
            <label
              for="app-drawer-sidemenu"
              class="daisy-btn daisy-btn-square daisy-btn-ghost lg:hidden"
            >
              <MenuIcon />
            </label>
            <router-link
              to="/"
              class="daisy-btn daisy-btn-ghost normal-case text-xl lg:hidden"
              >{{ APP_TITLE }}</router-link
            >
          </div>
          <div class="daisy-navbar-end">
            <div
              title="Change Language"
              class="daisy-dropdown daisy-dropdown-end"
            >
              <div
                tabindex="0"
                class="daisy-btn daisy-btn-ghost gap-1 normal-case"
              >
                <AccountIcon />
                <ChevronDownIcon />
              </div>
              <div
                class="daisy-dropdown-content bg-base-200 text-base-content rounded-t-box rounded-b-box top-px mt-16 w-56 overflow-y-auto shadow-2xl"
              >
                <ul
                  class="daisy-menu daisy-menu-compact gap-1 p-3"
                  tabindex="0"
                >
                  <li>
                    <button class="flex" @click.prevent="onLogout">
                      <LogoutIcon />
                      <span class="flex flex-1 justify-between">Logout </span>
                    </button>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </nav>
      </div>
      <div class="px-6 xl:pr-2 pb-16">
        <slot />
      </div>
    </div>
    <div class="daisy-drawer-side">
      <label for="app-drawer-sidemenu" class="daisy-drawer-overlay"></label>
      <ul class="daisy-menu p-4 w-80 bg-base-200 text-base-content">
        <div
          class="z-20 bg-base-200 bg-opacity-90 backdrop-blur sticky top-0 items-center gap-2 px-4 py-2 hidden lg:flex"
        >
          <router-link to="/" class="flex-0">
            <div
              class="font-title inline-flex text-lg transition-all duration-200 md:text-3xl"
            >
              {{ APP_TITLE }}
            </div>
          </router-link>
        </div>
        <li><router-link to="/"> Home </router-link></li>
        <li><router-link to="/mypage"> MyPage </router-link></li>
      </ul>
    </div>
  </div>
</template>
