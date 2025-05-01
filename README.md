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

## Modelo fisico de relaciones y entidades

```mermaid 
erDiagram
    USUARIOS {
        int id PK
        varchar(100) nombre
        varchar(100) correo UK
        varchar(255) password
        timestamp creado_en
    }

    ROLES {
        int id PK
        varchar(50) nombre UK
        text descripcion
    }

    USUARIO_ROL {
        int usuario_id PK,FK
        int rol_id PK,FK
    }

    USUARIOS ||--o{ USUARIO_ROL : "tiene"
    ROLES ||--o{ USUARIO_ROL : "asignado a"
```
---
## MER 

```mermaid
flowchart LR
    %% Entidades principales
    U[Usuarios]
    R[Roles]
    
    %% Atributos como entidades ovales
    U_ID([id])
    U_NOMBRE([nombre])
    U_CORREO([correo])
    U_PASSWORD([password])
    U_CREADO([creado_en])
    
    R_ID([id])
    R_NOMBRE([nombre])
    R_DESC([descripcion])
    
    %% Relación como diamante
    REL{{"Tiene"}}
    
    %% Conexiones con cardinalidades
    U --- |"N"| REL
    REL --- |"M"| R
    
    %% Conexiones a atributos
    U_ID --- U
    U_NOMBRE --- U
    U_CORREO --- U
    U_PASSWORD --- U
    U_CREADO --- U
    
    R_ID --- R
    R_NOMBRE --- R
    R_DESC --- R
```



---
## 🎨 Personalización

Puedes editar:

- **Estilos:** `assets/css/styles.css`
- **Scripts:** `assets/js/scripts.js`
- **Diseño general:** dentro de `layouts/`

---
