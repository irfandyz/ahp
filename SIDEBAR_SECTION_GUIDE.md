# Cara Menambahkan Section Title pada Sidebar

## Overview
Dokumen ini menjelaskan cara menambahkan section title pada sidebar aplikasi AHP dengan struktur yang terorganisir dan mudah dikelola.

## Struktur Section Title

### 1. Interface SidebarSection
```typescript
export interface SidebarSection {
    title: string;        // Judul section
    items: NavItem[];     // Array item navigasi
}
```

### 2. Interface NavItem
```typescript
export interface NavItem {
    title: string;        // Nama menu item
    href: string;         // URL tujuan
    icon?: LucideIcon;    // Icon (opsional)
    isActive?: boolean;   // Status aktif (opsional)
}
```

## Cara Menambahkan Section Baru

### Langkah 1: Definisikan Section di AppSidebar.vue
```typescript
const sidebarSections: SidebarSection[] = [
    {
        title: 'Dashboard',
        items: [
            {
                title: 'Overview',
                href: '/dashboard',
                icon: LayoutGrid,
            },
        ],
    },
    // Section baru
    {
        title: 'Master Data',
        items: [
            {
                title: 'Vendors',
                href: '/vendors',
                icon: Users,
            },
            {
                title: 'Categories',
                href: '/categories',
                icon: Tag,
            },
        ],
    },
];
```

### Langkah 2: Import Icon yang Diperlukan
```typescript
import { 
    Users, 
    Tag, 
    Building2,
    // Icon lainnya...
} from 'lucide-vue-next';
```

### Langkah 3: Tambahkan Route yang Sesuai
Pastikan route yang didefinisikan di `href` sudah ada di file routes Laravel.

## Contoh Section yang Sudah Ada

### 1. Dashboard Section
```typescript
{
    title: 'Dashboard',
    items: [
        {
            title: 'Overview',
            href: '/dashboard',
            icon: LayoutGrid,
        },
    ],
}
```

### 2. Expeditions Section
```typescript
{
    title: 'Expeditions',
    items: [
        {
            title: 'All Expeditions',
            href: '/expeditions',
            icon: Truck,
        },
        {
            title: 'Create New',
            href: '/expeditions/create',
            icon: Plus,
        },
        {
            title: 'Calendar View',
            href: '/expeditions/calendar',
            icon: Calendar,
        },
    ],
}
```

### 3. Master Data Section
```typescript
{
    title: 'Master Data',
    items: [
        {
            title: 'Vendors',
            href: '/vendors',
            icon: Users,
        },
        {
            title: 'Categories',
            href: '/categories',
            icon: Tag,
        },
        {
            title: 'Industry Sectors',
            href: '/industry-sectors',
            icon: Building2,
        },
    ],
}
```

## Fitur Section Title

### 1. Styling
- **Font**: Uppercase, semibold, text-xs
- **Color**: Muted foreground dengan hover effect
- **Spacing**: Proper padding dan margin

### 2. Responsive
- Section title tetap terlihat saat sidebar collapsed
- Item menu tersembunyi saat collapsed
- Tooltip muncul saat hover pada collapsed state

### 3. Active State
- Item menu yang aktif akan di-highlight
- Menggunakan `usePage()` untuk mendeteksi halaman aktif

## Menambahkan Section dengan Fitur Collapsible

### 1. Update SidebarSectionItem.vue
```typescript
interface Props {
    title: string;
    items: NavItem[];
    collapsible?: boolean;    // Enable collapsible
    defaultOpen?: boolean;     // Default state
}
```

### 2. Gunakan di AppSidebar.vue
```typescript
{
    title: 'Advanced Features',
    collapsible: true,
    defaultOpen: false,
    items: [
        // ... items
    ],
}
```

## Best Practices

### 1. Naming Convention
- Gunakan nama section yang jelas dan deskriptif
- Gunakan Pascal Case untuk section title
- Gunakan Title Case untuk item menu

### 2. Icon Selection
- Pilih icon yang relevan dengan fungsi menu
- Gunakan icon dari Lucide Vue Next
- Konsisten dalam penggunaan icon

### 3. URL Structure
- Gunakan URL yang RESTful
- Konsisten dalam penamaan route
- Hindari URL yang terlalu panjang

### 4. Grouping Logic
- Kelompokkan menu berdasarkan fungsi
- Pisahkan master data dari fitur utama
- Buat section terpisah untuk reports dan settings

## Troubleshooting

### 1. Section Tidak Muncul
- Periksa struktur data `sidebarSections`
- Pastikan semua property required terisi
- Check console untuk error

### 2. Icon Tidak Muncul
- Pastikan icon sudah di-import
- Periksa nama icon dari Lucide
- Pastikan component icon ter-render dengan benar

### 3. Route Tidak Bekerja
- Periksa file routes Laravel
- Pastikan route name sudah benar
- Check apakah middleware sudah sesuai

## Contoh Lengkap

```typescript
// AppSidebar.vue
const sidebarSections: SidebarSection[] = [
    {
        title: 'Dashboard',
        items: [
            {
                title: 'Overview',
                href: '/dashboard',
                icon: LayoutGrid,
            },
        ],
    },
    {
        title: 'Expeditions',
        items: [
            {
                title: 'All Expeditions',
                href: '/expeditions',
                icon: Truck,
            },
            {
                title: 'Create New',
                href: '/expeditions/create',
                icon: Plus,
            },
        ],
    },
    {
        title: 'Master Data',
        items: [
            {
                title: 'Vendors',
                href: '/vendors',
                icon: Users,
            },
            {
                title: 'Categories',
                href: '/categories',
                icon: Tag,
            },
        ],
    },
];
```

## Kesimpulan

Dengan struktur section title yang sudah dibuat, Anda dapat dengan mudah:
1. Menambahkan section baru
2. Mengelompokkan menu berdasarkan fungsi
3. Membuat sidebar yang terorganisir dan mudah dinavigasi
4. Menjaga konsistensi dalam design dan UX

Struktur ini memungkinkan pengembangan yang scalable dan maintainable untuk aplikasi AHP.
