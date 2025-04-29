# 🛠️ Sistema de Administración de Usuarios y Roles

Un sistema completo desarrollado en **PHP puro**, con interfaz en **HTML, CSS y JavaScript**, diseñado para la gestión eficiente de usuarios y roles, ideal para propósitos académicos o pequeños sistemas administrativos.

---

## 🚀 Características

- 🔐 Autenticación de usuarios (login/logout)
- 👥 CRUD de usuarios
- 🎭 CRUD de roles
- 🔗 Asignación de roles a usuarios
- 🛡️ Seguridad: validación, hash de contraseñas y prevención de inyecciones
- 📱 Interfaz responsive con diseño modular

---

## 🗂️ Estructura del Proyecto

```
/
├── index.php                # Dashboard principal
├── error.php                # Página de errores
├── .htaccess                # Reglas de reescritura (opcional)
│
├── assets/
│   ├── css/
│   │   └── styles.css       # Estilos personalizados
│   └── js/
│       └── scripts.js       # Lógica de interfaz
│
├── auth/
│   ├── login.php            # Formulario de inicio de sesión
│   └── logout.php           # Cierre de sesión
│
├── config/
│   ├── config.php           # Configuración general
│   └── database.php         # Conexión a la base de datos
│
├── includes/
│   ├── auth.php             # Control de autenticación
│   └── functions.php        # Funciones comunes reutilizables
│
├── layouts/
│   ├── header.php           # Encabezado
│   ├── footer.php           # Pie de página
│   └── sidebar.php          # Barra lateral
│
├── models/
│   ├── User.php             # Modelo de Usuario
│   └── Role.php             # Modelo de Rol
│
├── users/
│   ├── index.php            # Lista de usuarios
│   ├── add.php              # Crear nuevo usuario
│   ├── edit.php             # Editar usuario
│   ├── view.php             # Ver usuario
│   ├── delete.php           # Eliminar usuario
│   ├── profile.php          # Perfil del usuario
│   └── ajax/
│       └── update_user_role.php # Ajax para asignación rápida de rol
│
└── roles/
    ├── index.php            # Lista de roles
    ├── add.php              # Crear nuevo rol
    ├── edit.php             # Editar rol
    ├── view.php             # Ver rol
    ├── delete.php           # Eliminar rol
    └── update_user_role.php # Asignación de roles a usuarios
```

---

## ⚙️ Requisitos

- PHP 7.4 o superior  
- MySQL 5.7 o superior  
- Apache/Nginx (se recomienda Laragon para pruebas locales)  

---

## 🧩 Instalación

1. Clona el repositorio o descomprime los archivos.
2. Crea una base de datos en MySQL llamada `admin_usuarios`.
3. Importa el archivo `.sql` (por ejemplo, `users.sql`) para crear las tablas y datos base.
4. Edita el archivo `config/database.php` con tus credenciales de conexión.
5. Inicia el servidor desde Laragon y accede a `http://localhost/proc4`.

---

## 👤 Datos de acceso por defecto

| Rol        | Correo               | Contraseña |
|------------|----------------------|------------|
| Admin      | juan@example.com     | juan123    |
| Editor     | ana@example.com      | ana123     |
| Lector     | carlos@example.com   | carlos123  |

---

## 🔒 Seguridad Implementada

- Contraseñas hasheadas con `SHA-256`
- Validación del lado servidor
- Prevención de SQL Injection usando consultas seguras
- Sanitización de salidas para evitar XSS
- Control de acceso por sesión y roles

---

## 🎨 Personalización

Puedes editar:

- **Estilos:** `assets/css/styles.css`
- **Scripts:** `assets/js/scripts.js`
- **Diseño general:** dentro de `layouts/`

---
