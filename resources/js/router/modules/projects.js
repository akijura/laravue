/** When your routing table is too long, you can split it into small modules**/

import Layout from '@/layout';

const componentRoutes = {
  path: '/projects',
  component: Layout,
  redirect: 'noredirect',
  name: 'Projects',
  meta: {
    title: 'projects',
    icon: 'component',
    permissions: ['view menu projects'],
  },
  children: [
    {
      path: 'projectsList',
      component: () => import('@/views/projects/index'),
      name: 'projectsList',
      meta: { title: 'projectsList' },
      permissions: ['view menu projectsList'],
    },
    {
      path: 'markdown',
      component: () => import('@/views/components-demo/Markdown'),
      name: 'MarkdownDemo',
      meta: { title: 'markdown' },
    },
  ],
};

export default componentRoutes;
