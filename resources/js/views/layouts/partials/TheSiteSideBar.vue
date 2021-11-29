<template>
    <div class="main-sidebar sidebar-style-2 position-fixed">
        <aside id="sidebar-wrapper">
            <div class="sidebar-brand">
                <router-link :to="{ name: 'dashboard'}" class="text-brand h3 m-0"
                    ><img
                        src="/assets/images/logo/fizzyfest-logo.png"
                        alt="fizzyfest-logo"
                    />
                </router-link>
            </div>
            <div class="sidebar-brand sidebar-brand-sm">
                <router-link
                    to="{ name: 'dashboard'}"
                    class="text-brand h3 m-0"
                >
                    <img
                        src="/assets/images/logo/fizzyfest-shape.png"
                        alt="fizzyfest-logo"
                    />
                </router-link>
            </div>

            <ul class="sidebar-menu">
                <li
                    v-for="(menuItem, index) in authRoute()"
                    :key="index"
                    :class="{
                        dropdown: !menuItem.path,
                        active: isCurrentRoute(menuItem)
                    }"
                >
                    <router-link
                        v-if="menuItem.path"
                        :to="{ name: menuItem.path }"
                        class="nav-link"
                    >
                        <i class="fas" :class="[menuItem.icon]"></i>
                        <span>{{ menuItem.text }}</span>
                    </router-link>

                    <a
                        href="#"
                        v-else
                        class="nav-link has-dropdown"
                        data-toggle="dropdown"
                    >
                        <i class="fas" :class="[menuItem.icon]"></i
                        ><span>{{ menuItem.text }}</span>
                    </a>
                    <ul v-if="!menuItem.path" class="dropdown-menu">
                        <router-link
                            v-for="(subMenuItem, index) in menuItem.subMenuList"
                            :key="index"
                            tag="li"
                            :to="{ name: subMenuItem.path }"
                        >
                            <a class="nav-link">
                                {{ subMenuItem.text }}
                            </a>
                        </router-link>
                    </ul>
                </li>
            </ul>
            <div class="mt-4 mb-4 p-3 hide-sidebar-mini">
                <a
                    href="https://getstisla.com/docs"
                    class="btn btn-info btn-lg btn-block btn-icon-split"
                >
                    <i class="fas fa-question-circle"></i> Need help?
                </a>
            </div>
        </aside>
    </div>
</template>

<script>
export default {
    data() {
        return {
            menuList: [
                {
                    role: "admin",
                    text: "Dashboard",
                    icon: "fa-fire",
                    subMenuList: [
                        {
                            text: "Global",
                            path: "admin.dashboard"
                        },
                        { text: "My a/c", path: "dashboard" }
                    ]
                },
                {
                    role: "user",
                    text: "Dashboard",
                    icon: "fa-fire",
                    path: "dashboard"
                },
                {
                    role: "admin",
                    text: "Users",
                    path: "users.index",
                    icon: "fa-user",
                    subMenuList: [{ text: "User details", path: "users.view" }]
                },
                {
                    role: "user",
                    text: "Discover",
                    path: "discover",
                    icon: "fa-compass",
                    subMenuList: [
                        { text: "Discover type ", path: "discover.type" },
                        { text: "Discover view", path: "discover.view" }
                    ]
                },
                {
                    text: "Fizzies",
                    path: "fizzies.index",
                    icon: "fa-gamepad",
                    subMenuList: [
                        { text: "Create fizzy", path: "fizzies.create" },
                        { text: "Create details", path: "fizzies.view" }
                    ]
                },
                {
                    text: "Reports",
                    path: "reports.index",
                    icon: "fa-poll",
                    subMenuList: [{ text: "Report view", path: "reports.view" }]
                },
                {
                    role: "admin",
                    text: "CMS",
                    path: "cms.index",
                    icon: "fa-mountain"
                },
                {
                    role: "admin",
                    text: "Setting",
                    icon: "fa-cog",
                    subMenuList: [
                        { text: "Account setting", path: "settings.profile" },
                        {
                            text: "Change password",
                            path: "settings.changepassword"
                        }
                    ]
                },
                {
                    role: "user",
                    text: "Setting",
                    icon: "fa-cog",
                    subMenuList: [
                        { text: "Account setting", path: "settings.profile" },
                        {
                            text: "Change password",
                            path: "settings.changepassword"
                        },
                        {
                            role: "user",
                            text: "Delete account",
                            path: "settings.deleteaccount"
                        }
                    ]
                }
            ]
        };
    },
    methods: {
        isCurrentRoute(menuItem) {
            let submenulist = menuItem.subMenuList;
            if (menuItem.path === this.$route.name) {
                return true;
            }
            if (submenulist) {
                for (var i = 0; i < submenulist.length; i++)
                    if (submenulist[i].path === this.$route.name) return true;
            }
        },
        authRoute() {
            return this.menuList.filter(this.setMenuList);
        },
        setMenuList(menuItem) {
            if (menuItem.role == "admin") {
                if (this.$store.getters.activeUserRole == 1) {
                    return menuItem;
                } else {
                    return;
                }
            } else if (menuItem.role == "user") {
                if (this.$store.getters.activeUserRole == 2) {
                    return menuItem;
                } else {
                    return;
                }
            } else {
                return menuItem;
            }
        }
    }
};
</script>
