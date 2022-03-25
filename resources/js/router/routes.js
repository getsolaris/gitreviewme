function page(path) {
  return () => import(/* webpackChunkName: '' */ `~/pages/${path}`).then(m => m.default || m)
}

export default [
  {path: '/', name: 'home', component: page('home.vue')},

  {path: '/login', name: 'login', component: page('auth/login-github.vue')},
  {path: '/register', name: 'register', component: page('auth/register-github.vue')},
  {path: '/password/reset', name: 'password.request', component: page('auth/password/email.vue')},
  {path: '/password/reset/:token', name: 'password.reset', component: page('auth/password/reset.vue')},
  {path: '/email/verify/:id', name: 'verification.verify', component: page('auth/verification/verify.vue')},
  {path: '/email/resend', name: 'verification.resend', component: page('auth/verification/resend.vue')},
  {path: '/projects', name: 'projects.index', component: page('projects/index.vue')},
  {path: '/projects/:id', name: 'projects.show', component: page('projects/show.vue')},
  // {
  //   path: '/projects',
  //   component: page('projects/index.vue'),
  //   children: [
  //     {path: '', name: 'projects.index', component: page('projects/index.vue')},
  //     {path: ':id', name: 'projects.show', component: page('projects/show.vue')},
  //     {path: 'add', name: 'projects.add', component: page('projects/add.vue')},
  //   ]
  // },
  {
    path: '/reviewers',
    component: page('reviewers/index.vue'),
    children: [
      {path: '', name: 'reviewers.index', component: page('reviewers/index.vue')},
    ]
  },
  {
    path: '/profile',
    component: page('profiles/index.vue'),
    children: [
      {path: '', name: 'profile.index', component: page('profiles/index.vue')},
    ]
  },
  {
    path: '/u',
    component: page('profiles/handle.vue'),
    children: [
      {path: ':handle', name: 'profile.handle', component: page('profiles/handle.vue')},
      {path: ':handle/repositories', name: 'profile.handle', component: page('profiles/handle.vue')},
      {path: ':handle/projects', name: 'profile.handle', component: page('profiles/handle.vue')},
      {path: ':handle/settings', name: 'profile.handle', component: page('profiles/handle.vue')},
    ]
  },
  {
    path: '/settings',
    component: page('settings/index.vue'),
    children: [
      {path: '', redirect: {name: 'settings.profile'}},
      {path: 'profile', name: 'settings.profile', component: page('settings/profile.vue')},
      {path: 'password', name: 'settings.password', component: page('settings/password.vue')}
    ]
  },

  {path: '*', component: page('errors/404.vue')}
]
