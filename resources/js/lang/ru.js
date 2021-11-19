export default {
  route: {
    dashboard: 'Панель управления',
    introduction: 'Введение',
    documentation: 'Документация',
    guide: 'Инструкция',
    permission: 'Права',
    pagePermission: 'Страница прав',
    rolePermission: 'Права ролей',
    directivePermission: 'Права для путей',
    icons: 'Иконки',
    components: 'Компоненты',
    projects: 'Проекты',
    projectsList: 'Список проектов',
    componentIndex: 'Введение',
    tinymce: 'Tinymce',
    markdown: 'Markdown',
    jsonEditor: 'JSON Editor',
    dndList: 'Dnd List',
    splitPane: 'SplitPane',
    avatarUpload: 'Загрузка аватара',
    dropzone: 'Dropzone',
    sticky: 'Sticky',
    countTo: 'CountTo',
    componentMixin: 'Mixin',
    backToTop: 'Кнопка "Вверх"',
    dragDialog: 'Drag Dialog',
    dragSelect: 'Drag Select',
    dragKanban: 'Drag Kanban',
    charts: 'Графики',
    keyboardChart: 'Столбчатый график',
    lineChart: 'Линейный график',
    mixChart: 'Смешанный график',
    example: 'Пример',
    nested: 'Вложенные меню',
    menu1: 'Меню 1',
    'menu1-1': 'Меню 1-1',
    'menu1-2': 'Меню 1-2',
    'menu1-2-1': 'Меню 1-2-1',
    'menu1-2-2': 'Меню 1-2-2',
    'menu1-3': 'Меню 1-3',
    menu2: 'Меню 2',
    table: 'Таблица',
    dynamicTable: 'Динамическая',
    dragTable: 'С переносом строк',
    inlineEditTable: 'С редактированием',
    complexTable: 'Комплексная',
    treeTable: 'Древовидная',
    customTreeTable: 'Кастомное древо',
    tab: 'Вкладки',
    form: 'Форма',
    createArticle: 'Создать статью',
    editArticle: 'Изменить статью',
    articleList: 'Статьи',
    errorPages: 'Страницы ошибок',
    page401: '401',
    page404: '404',
    errorLog: 'Лог ошибок',
    excel: 'Excel',
    exportExcel: 'Экспорт в Excel',
    selectExcel: 'Экспорт выбранных строк',
    mergeHeader: 'Склееные заголовки',
    uploadExcel: 'Импорт Excel файла',
    zip: 'Zip',
    pdf: 'PDF',
    exportZip: 'Экспорт в Zip',
    theme: 'Тема',
    clipboardDemo: 'Буфер обмена',
    i18n: 'I18n',
    externalLink: 'Внешняя ссылка',
    elementUi: 'Element UI',
    status: 'Статус',
    manage_status: 'Управлять статусами',
    administrator: 'Администратор',
    users: 'Пользователи',
    userProfile: 'Профиль пользователя',
    projectDetail: 'Детали проекта',
    notification: 'Уведомление',
    channels: 'Каналы',
    userCredentials: 'Идентификаторы',
  },
  navbar: {
    logOut: 'Выход',
    dashboard: 'Панель управления',
    github: 'Github',
    theme: 'Тема',
    size: 'Размер интерфейса',
    profile: 'Профиль',
  },
  login: {
    title: 'Авторизация',
    logIn: 'Войти',
    username: 'Username',
    password: 'Пароль',
    any: 'любой',
    thirdparty: 'Или войдите с помощью',
    thirdpartyTips: 'Can not be simulated on local, so please combine you own business simulation! ! !',
    email: 'Email',
  },
  documentation: {
    documentation: 'Документация',
    laravel: 'Laravel',
    github: 'Репозиторий Github',
  },
  permission: {
    addRole: 'Новая роль',
    editPermission: 'Редактировать право',
    roles: 'Ваши роли',
    switchRoles: 'Сменить роль',
    tips: 'В некоторых случаях не рекомендуется использовать v-role/v-permission, например в Tab компонентах или в el-table-column, и в других  элементах, у которых dom структура рендерится асинхронно. Вместо этого используйте v-if с checkRole и/или checkPermission.',
    delete: 'Удалить',
    confirm: 'Подтвердить',
    cancel: 'Отменить',
  },
  guide: {
    description: 'Инструкция полезна для тех, кто использует этот проект впервые. Вы можете кратко ознакомится с ним. Демо основано на',
    button: 'Показать инструкцию',
  },
  components: {
    documentation: 'Документация',
    tinymceTips: 'Rich text редактор является основной частью систем управления, но также у него имеется множество проблем. Выбирая rich text редактор, я испробовал разные. В основном все используют обычные rich text редакторы, но в итоге выбирают Tinymce. Смотрите документацию для подробных сравнений и ознакомления.',
    dropzoneTips: 'Поскольку у моего бизнеса есть нужды в особом функционале, и он должен загружать изображения в qiniu, вместо сторонних библиотек я инкапсулировал dropzone сам.  Это очень просто, вы можете увидеть подробный код в @/components/Dropzone.',
    stickyTips: 'Когда страница спускается до заданной позиции, элемент приклеивается к верху.',
    backToTopTips1: 'Когда страница спускается до заданной позиции, кнопка "подняться вверх" появляется в ннижнем правом углу.',
    backToTopTips2: 'Вы можете изменить стили кнопки, анимацию появления/исчезания, высоту на которой она появится/исчезнет. Если Вам нужно отобразить текст, вы можете использовать element-ui el-tooltip внешне, как в примере.',
    imageUploadTips: 'Ввиду того, что я использовал версию vue@1, и в данный момент она не совместима с mockjs, я модифицировал её сам. И если вы собираетесь её использовать, лучше использовать официальную версию.',
  },
  table: {
    description: 'Описание',
    dynamicTips1: 'Фиксированная ширина столбцов, фиксированный порядок столбцов',
    dynamicTips2: 'Изменяемая ширина столбцов, сортировка порядка столбцов по клику',
    dragTips1: 'Изначальный порядок элементов',
    dragTips2: 'Порядок после перетаскивания элементов',
    name: 'Имя',
    title: 'Заголовок',
    importance: 'Важность',
    type: 'Тип',
    remark: 'Примечание',
    search: 'Поиск',
    add: 'Добавить',
    export: 'Экспорт',
    reviewer: 'Проверяющий',
    id: 'ID',
    date: 'Дата',
    author: 'Автор',
    readings: 'Просмотрено',
    status: 'Статус',
    actions: 'Действия',
    edit: 'Изменить',
    publish: 'Опубликовать',
    draft: 'Черновик',
    delete: 'Удалить',
    cancel: 'Отменить',
    confirm: 'Подтвердить',
    keyword: 'Ключевое слово',
    role: 'Роль',
    warning: 'Предупреждение',
  },
  errorLog: {
    tips: 'Пожалуйста, нажмите на иконку "бага" в правом верхнем углу',
    description: 'Сейчас система управления это SPA (single page application). Это улучшает удобство интерфейса, но так же увеличивает вероятность появления ошибок, которые могут привести к тупиковой странице (т.е. придеться перезагрузить страницу). К счастью Vue предоставляет перехват исключений, где вы можете обработать ошибку или сообщить об исключении.',
    documentation: 'Введение в документацию',
  },
  excel: {
    export: 'Экспорт',
    selectedExport: 'Экспортировать выбранные строки',
    placeholder: 'Пожалуйста, введите название файла (по умолчанию excel-list)',
  },
  zip: {
    export: 'Экспорт',
    placeholder: 'Пожалуйста, введите название файла (по умолчанию file)',
  },
  pdf: {
    tips: 'Здесь мы используем window.print(), для реализации скачивания pdf файла.',
  },
  theme: {
    change: 'Изменить тему',
    documentation: 'Документация по темам',
    tips: 'Подсказка: Это отличается от смены темы на панели навигации, это два разных метода смены темы, с разными реализациями кода. Больше информации, в документации.',
  },
  tagsView: {
    refresh: 'Обновить',
    close: 'Закрыть',
    closeOthers: 'Закрыть остальные',
    closeAll: 'Закрыть все',
  },
  settings: {
    title: 'Настройка стилей страниц',
    theme: 'Цвет темы',
    tagsView: 'Отображать вкладки',
    fixedHeader: 'Зафиксировать панель навигации',
    sidebarLogo: 'Логотип на боковой панели',
  },
  user: {
    'role': 'Роль',
    'password': 'Пароль',
    'confirmPassword': 'Подтвердить пароль',
    'name': 'Имя',
    'email': 'Email',
    'create_role': 'Создать новую роль',
  },
  roles: {
    description: {
      admin: 'Super Administrator. Есть доступ и права для всех страниц',
      manager: 'Manager. Есть доступ и права к большинству страниц, кроме страницы прав.',
      editor: 'Editor. Имеет доступ к большинству страниц, все права для статей и связанных ресурсов.',
      user: 'Normal user. Есть доступ к некоторым страницам.',
      visitor: 'Visitor. Имеют доступ к статическим страницам, не должны иметь прав на запись.',
    },
  },
  main_status: {
    'active': 'Актив',
    'nonActive': 'Не активен',
    'statusName': 'Название статуса',
    'statusDesc': 'Описание статуса',
    'status': 'Статус',
    'text_dialog': 'Создать новый базовый статус',
    'text_dialog_status': 'Создать новый статус для базового типа',
    'created_at': 'Дата создания',
    'id': 'Номер',
    'actions': 'Действия',
    'text_dialog_status_edit': 'Изменить статус',
    'update': 'Изменить',
    'statusQueue': 'Очередь статуса',
    'basicStatus': 'Базовый статус',
    'after': 'После',
    'percent': 'Процент',

  },
  projects: {
    'todo': 'Начало',
    'working': 'Работающий',
    'done': 'Выполнено',
    'cancelled': 'Отмененный',
    'text_dialog': 'Создать новый проект',
    'projectName': 'Название проекта',
    'projectDescription': 'Описание Проекта',
    'projectBeginDate': 'Дата начала проекта',
    'projectEndDate': 'Дата окончания проекта',
    'period': 'Период проекта',
    'projectExecutors': 'Исполнители проекта',
    'projectComment': 'Комментарий к проекту',
    'project_level': 'Приоритет проекта',
    'createdAt': 'Создано в',
    'changeTypeStatus': 'Изменить статус',
    'creator': 'Создатель',
    'add_member': 'Добавить участника проекта',
    'files': 'Файлы',
    'comment': 'Комментарий',
    'about': 'О проекте',
    'proccess': 'Процесс',
    'deadline': 'Предельный срок',
    'change_status': 'Изменение Статусы',
    'priority': 'Приоритет',
    'from': 'От',
    'to': 'До',
    'dayspro': 'Дни для проекта',
    'raminedays': 'Оставшиеся дни',
    'up': 'вверх',
    'confirmed': 'Подтверждено',
    'not_confirmed': 'Не подтверждено',
  },

  notification: {
    'channelName': 'Название канала',
    'channelApiLink': 'Ссылка на API',
    'createNewChannel': 'Создать новый канал',
    'editChannel': 'Изменить канал',
    'channelUpdated': 'Канал успешно обновлен!',
    'channelCreated': 'Новый канал успешно создан!',
    'channelDeleteWarning': (ctx) => 'Это приведет к безвозвратному удалению канала ' + ctx.named('channelName') + '. Продолжать?',
    'channelDeleted': 'Канал успешно удален',
    'identifier': 'Идентификатор',
    'addNewCredential': 'Новый идентификатор',
    'credentialAdded': (ctx) => 'Новые учетные данные пользователя ' + ctx.named('userName') + ' успешно добавлены!',
    'newCredential': (ctx) => 'Новые учетные данные для ' + ctx.named('userName'),
    'editCredential': 'Изменить учетные данные пользователя',
    'credentialUpdated': 'Учетные данные пользователя успешно обновлены',
    'credentialDeleteWarning': 'Это приведет к безвозвратному удалению учетных данных для уведомлений. Продолжать?',
  },
};
