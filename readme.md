# 🦷 Sistema de Historias Clínicas Odontológicas

## 🎓 Proyecto Académico

Desarrollado por **José Guanda** para la materia **Aplicaciones Web** (Universidad Dr. José Gregorio Hernández, UJGH).

Aplicación web para llevar la historia clínica de pacientes que se realizan procedimientos odontológicos (limpieza, blanqueamiento, extracción de muelas o cordales, tratamiento de conductos, calzas, etc.). Funciona con peticiones AJAX, sin recargar la página, y responde en formato JSON.

🔗 **Aplicación publicada:** http://ujgh-clinica.infinityfreeapp.com

## ✨ Funcionalidades

- **Inicio de sesión** con contraseñas cifradas y páginas protegidas por sesión.
- **Panel principal** con resumen de pacientes, procedimientos y registros clínicos, y menú de navegación.
- **Pacientes:** registrar, listar, editar y eliminar.
- **Procedimientos:** registrar, listar, editar y eliminar los que ofrece el odontólogo.
- **Historia clínica:** buscar al paciente por cédula, nombre o apellido, ver su historia y agregar registros nuevos.
- **PDF:** generar la historia clínica del paciente.

## 🛠️ Tecnologías Utilizadas

- **Frontend:** HTML5, CSS3, Bootstrap 4, Font Awesome 4.7 y jQuery 3.6.0.
- **Backend:** PHP con la extensión nativa MySQLi.
- **Base de datos:** MySQL (InnoDB, utf8mb4).
- **Comunicación:** AJAX con respuestas JSON.
- **PDF:** librería FPDF.

## 📁 Estructura del Proyecto

```
clinica/
│
├── index.php               # Inicio de sesión
├── panel.php               # Panel principal
├── pacientes.php           # Gestión de pacientes
├── procedimientos.php      # Gestión de procedimientos
├── historial.php           # Historia clínica
├── database.sql            # Script de la base de datos
├── readme.md               # Documentación
│
├── includes/               # Piezas reutilizables
│   ├── sesion.php          # Protege las páginas (exige sesión iniciada)
│   ├── header.php          # <head>, Bootstrap y estilos
│   ├── menu.php            # Menú de navegación
│   └── footer.php          # Scripts comunes
│
├── assets/
│   ├── css/app.css         # Estilos personalizados
│   └── js/                 # AJAX de cada módulo
│       ├── login.js
│       ├── pacientes.js
│       ├── procedimientos.js
│       └── historial.js
│
├── php/                    # Backend: responde a las peticiones AJAX
│   ├── conexion.php        # Conexión a MySQL
│   ├── login.php
│   ├── logout.php
│   ├── pacientes/          # listar, agregar, editar, eliminar, seleccionar, buscar
│   ├── procedimientos/     # listar, agregar, editar, eliminar, seleccionar
│   └── historial/          # listar, agregar
│
└── pdf/
    ├── generar_pdf.php     # Genera la historia clínica en PDF
    └── fpdf/               # Librería FPDF
```

## 🗄️ Base de Datos

Cuatro tablas relacionadas mediante llaves foráneas:

| Tabla | Descripción |
|---|---|
| `usuarios` | Odontólogos que inician sesión |
| `pacientes` | Datos personales de cada paciente |
| `procedimientos` | Catálogo de procedimientos y su precio |
| `historial` | Une paciente + procedimiento + odontólogo, con fecha, pieza dental y observaciones |

Las llaves foráneas usan `ON DELETE RESTRICT`: no se puede eliminar un paciente, un procedimiento o un usuario que ya tenga historia clínica registrada.

## ⚙️ Instalación Local

### Requisitos

- Servidor local como XAMPP, WAMP o Laragon.
- PHP 7.4 o superior (con la extensión `mbstring` activada).
- MySQL / MariaDB.

### Pasos

1. **Clonar el repositorio** dentro de la carpeta pública de tu servidor (`htdocs` en XAMPP):

   ```
   git clone https://github.com/jguanda2405-dev/clinica-odontologica.git
   ```

2. **Importar la base de datos:** abre phpMyAdmin e importa `database.sql`. Esto crea la base `clinica_db`, sus tablas y datos de ejemplo.

3. **Revisar la conexión:** en `php/conexion.php` ajusta las credenciales si las tuyas son distintas:

   ```php
   $servidor = "localhost";
   $usuario  = "root";
   $password = "";
   $database = "clinica_db";
   ```

4. **Crear el primer usuario.** El script no incluye usuarios, porque las contraseñas se guardan con hash. Genera el hash en una terminal:

   ```
   php -r "echo password_hash('TU_CONTRASEÑA', PASSWORD_DEFAULT);"
   ```

   Y luego insértalo en phpMyAdmin (pestaña SQL):

   ```sql
   INSERT INTO usuarios (nombre, apellido, correo, contrasena)
   VALUES ('Nombre', 'Apellido', 'correo@ejemplo.com', 'PEGA_AQUI_EL_HASH');
   ```

5. **Ejecutar:** abre `http://localhost/clinica/` e inicia sesión con ese usuario.

## 🔑 Usuario de Prueba (aplicación publicada)

- **Correo:** `admin@clinica.com`
- **Contraseña:** `123456`

## 🔒 Seguridad

- **Contraseñas:** cifradas con `password_hash()` y verificadas con `password_verify()`.
- **Inyección SQL:** IDs convertidos con `(int)` y textos con `mysqli_real_escape_string`.
- **Sesiones:** cada página y cada archivo del backend verifican que haya sesión iniciada. El odontólogo de cada registro clínico se toma de la sesión, no del formulario.
- **XSS:** los datos se escapan antes de mostrarse en pantalla.
- **Errores:** los errores de la base de datos (duplicados, llaves foráneas) se convierten en mensajes claros y no se expone información interna.

## 👨‍💻 Autoría

Desarrollado por **José Guanda** con fines educativos, tomando como guía el proyecto `practica_db` del profesor Juan Carlos Medina.

**Licencia:** libre para uso académico y de aprendizaje. La generación de PDF usa [FPDF](http://www.fpdf.org/).