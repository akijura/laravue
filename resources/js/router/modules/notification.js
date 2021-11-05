import Layout from '@/layout';

const notificationRoutes = {
  path: '/notification',
  component: Layout,
  redirect: '/notification/channels',
  name: 'notification',
  meta: {
    title: 'Notification',
    icon: 'guide',
  },
  children: [
    {
      path: 'channels',
      name: 'channels',
      component: () => import('@/views/notifications/channels'),
      meta: { title: 'Channels', icon: 'international' },
    },
    {
      path: 'user-credentials',
      name: 'UserCredentials',
      component: () => import('@/views/notifications/user-credentials'),
      meta: { title: 'User credentials', icon: 'lock' },
    },
  ],

};
export default notificationRoutes;
