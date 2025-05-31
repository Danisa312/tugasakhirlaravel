import 'bootstrap';
import { menuItems } from './menuData';

function renderSidebar() {
    const navList = document.querySelector('#navbar-items');
    if (!navList) return;

    menuItems.forEach(item => {
        const li = document.createElement('li');
        li.className = 'nav-item';

        if (item.child && item.child.length > 0) {
            const subMenu = item.child.map(sub => `
                <li class="nav-item ms-3">
                    <a class="nav-link text-white" href="${sub.link}">${sub.label}</a>
                </li>
            `).join('');

            li.innerHTML = `
                <a class="nav-link fw-bold text-white" href="${item.link}">${item.label}</a>
                <ul class="nav flex-column">
                    ${subMenu}
                </ul>
            `;
        } else {
            li.innerHTML = `<a class="nav-link text-white" href="${item.link}">${item.label}</a>`;
        }

        navList.appendChild(li);
    });
}

document.addEventListener('DOMContentLoaded', renderSidebar);