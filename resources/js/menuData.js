// resources/js/menuData.js

let nextId = 1;

export const menuItems = [
    {
        id: nextId++,
        label: 'Beranda',
        link: '/',
        child: []
    },
    {
        id: nextId++,
        label: 'Fitur',
        link: '/fitur',
        child: []
    },
    {
        id: nextId++,
        label: 'Tentang',
        link: '/tentang',
        child: []
    },
    {
        id: nextId++,
        label: 'Layanan',
        link: '#',
        child: [
            {
                id: nextId++,
                label: 'Web Development',
                link: '/layanan/web'
            },
            {
                id: nextId++,
                label: 'Mobile App',
                link: '/layanan/mobile'
            }
        ]
    }
];
