import Layout from '@/layout';

const elementUiRoutes = {
  path: '/element-ui',
  component: Layout,
  redirect: '/element-ui/form',
  name: 'Status',
  meta: {
    title: 'status',
    icon: 'layout',
    permissions: ['view menu status'],
  },
  children: [
    // {
    //   path: 'form',
    //   name: 'Form',
    //   component: () => import('@/views/form/index'),
    //   meta: { title: 'form', icon: 'form' },
    // },
    // {
    //   path: 'icons',
    //   component: () => import('@/views/icons/index'),
    //   name: 'Icons',
    //   meta: { title: 'icons', icon: 'el-icon-info', noCache: true },
    // },
    {
      path: 'tab',
      component: () => import('@/views/tab'),
      name: 'Manage Statuses',
      meta: { title: 'manage_status', icon: 'tab' },
      permissions: ['view menu status inside'],
    },
  ],
};

export default elementUiRoutes;
