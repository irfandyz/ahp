# UI Components Guide - Calendar Feature

## Overview
Dokumen ini menjelaskan komponen UI yang telah dibuat untuk mendukung fitur Calendar View dalam aplikasi AHP.

## Komponen yang Dibuat

### 1. **Textarea Component**
**File**: `resources/js/components/ui/textarea/Textarea.vue`

**Fitur**:
- Support untuk `v-model` dengan `modelValue`
- Customizable rows, placeholder, disabled, readonly
- Styling yang konsisten dengan design system
- Event handling untuk input changes

**Usage**:
```vue
<Textarea
  v-model="description"
  placeholder="Enter description..."
  :rows="5"
  class="custom-class"
/>
```

**Props**:
- `modelValue`: String value untuk v-model
- `placeholder`: Placeholder text
- `rows`: Jumlah baris (default: 3)
- `disabled`: Disable state
- `readonly`: Readonly state
- `class`: Additional CSS classes

### 2. **Select Component System**
**Files**:
- `Select.vue` - Main select container
- `SelectTrigger.vue` - Trigger button
- `SelectContent.vue` - Dropdown content
- `SelectItem.vue` - Individual option items
- `SelectValue.vue` - Display selected value

**Fitur**:
- Dropdown select dengan state management
- Keyboard navigation support
- Click outside untuk close
- Customizable styling
- Accessibility features

**Usage**:
```vue
<Select v-model="selectedType">
  <SelectTrigger>
    <SelectValue placeholder="Select type" />
  </SelectTrigger>
  <SelectContent>
    <SelectItem value="custom">Custom Note</SelectItem>
    <SelectItem value="reminder">Reminder</SelectItem>
  </SelectContent>
</Select>
```

**Props**:
- `modelValue`: Selected value
- `disabled`: Disable state
- `class`: Additional CSS classes

## Struktur File

```
resources/js/components/ui/
├── textarea/
│   ├── Textarea.vue
│   └── index.ts
└── select/
    ├── Select.vue
    ├── SelectContent.vue
    ├── SelectItem.vue
    ├── SelectTrigger.vue
    ├── SelectValue.vue
    └── index.ts
```

## Integration dengan Calendar

### 1. **Calendar Form Components**
Komponen ini digunakan dalam Calendar View untuk:
- **Add Note Dialog**: Form untuk menambah note baru
- **Edit Note Dialog**: Form untuk edit note existing
- **Type Selection**: Dropdown untuk memilih jenis note
- **Priority Selection**: Dropdown untuk memilih prioritas
- **Description Input**: Textarea untuk detail note

### 2. **State Management**
- **Select Component**: Menggunakan provide/inject pattern
- **Form Validation**: Support untuk validation rules
- **Event Handling**: Proper event emission dan handling

## Styling dan Design

### 1. **CSS Classes**
- **Base Classes**: Konsisten dengan design system
- **State Classes**: Hover, focus, disabled states
- **Responsive**: Mobile-friendly design
- **Accessibility**: Proper focus indicators

### 2. **Color Scheme**
- **Primary**: Border dan focus rings
- **Background**: Light/dark mode support
- **Text**: Proper contrast ratios
- **States**: Hover dan focus colors

## Best Practices

### 1. **Component Design**
- **Single Responsibility**: Setiap komponen punya satu tugas
- **Reusability**: Komponen dapat digunakan di berbagai tempat
- **Props Interface**: TypeScript interfaces untuk type safety
- **Event Handling**: Proper event emission

### 2. **Performance**
- **Lazy Loading**: Komponen load saat diperlukan
- **Event Delegation**: Efficient event handling
- **Memory Management**: Proper cleanup di onUnmounted

### 3. **Accessibility**
- **Keyboard Navigation**: Support untuk keyboard users
- **Screen Readers**: Proper ARIA labels
- **Focus Management**: Visible focus indicators
- **Color Contrast**: WCAG compliance

## Troubleshooting

### 1. **Build Errors**
- **Import Issues**: Pastikan path import benar
- **TypeScript Errors**: Check interface definitions
- **Vue Compiler**: Verify Vue syntax

### 2. **Runtime Issues**
- **Component Not Found**: Check component registration
- **Props Errors**: Verify prop types dan defaults
- **Event Issues**: Check event emission dan handling

### 3. **Styling Issues**
- **CSS Conflicts**: Check class naming
- **Responsive Issues**: Test di berbagai screen sizes
- **Theme Issues**: Verify color scheme consistency

## Future Enhancements

### 1. **Advanced Features**
- **Multi-select**: Support untuk multiple selection
- **Search**: Filter options dengan search
- **Groups**: Grouped options
- **Custom Rendering**: Custom option templates

### 2. **Integration**
- **Form Libraries**: Integration dengan form libraries
- **Validation**: Built-in validation support
- **Theming**: Advanced theming system
- **Internationalization**: Multi-language support

## Kesimpulan

Komponen UI yang dibuat memberikan foundation yang solid untuk:
1. **Form Inputs**: Textarea dan Select components
2. **User Experience**: Intuitive dan accessible interfaces
3. **Design Consistency**: Unified design language
4. **Maintainability**: Clean, reusable code structure

Komponen ini terintegrasi dengan baik dalam Calendar View dan dapat digunakan kembali untuk fitur-fitur lain dalam aplikasi AHP.
