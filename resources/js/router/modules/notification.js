import Layout from '@/layout';

const notificationRoutes = {
  path: '/notification',
  component: Layout,
  redirect: '/notification/channels',
  name: 'notification',
  meta: {
    title: 'notification',
    icon: 'guide',
    permissions: ['view menu notification channels'],
  },
  children: [
    {
      path: 'channels',
      name: 'channels',
      component: () => import('@/views/notifications/channels'),
      meta: { title: 'channels', icon: 'international' },
    },
    {
      path: 'user-credentials',
      name: 'userCredentials',
      component: () => import('@/views/notifications/user-credentials'),
      meta: { title: 'userCredentials', icon: 'lock' },
    },
  ],

};
export default notificationRoutes;
