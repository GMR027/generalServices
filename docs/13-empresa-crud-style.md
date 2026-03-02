# Paso 13: Estilos y subida de logo

Hemos adaptado los estilos del proyecto de referencia (`solucionesEnAcero`) a este sistema. Para verlos en acción debes ejecutar Gulp (ahora hemos añadido unos scripts npm para simplificarlo):

```bash
npm install          # si no lo has hecho todavía
npm run dev          # ejecuta gulp css && gulp js
# o manualmente:
npx gulp css         # compila SCSS a public/build/css/app.css
npx gulp js          # para copiar JS si hace falta
```

Después recarga la página (f5 en el navegador o limpia caché); todos los formularios, tablas y la navegación ahora usan los colores, fuentes y botones definidos en nuestros parciales SCSS.

> ⚠️ Si no ejecutas estas instrucciones no verás cambios de color ni estilos nuevos, ya que la hoja `public/build/css/app.css` no se actualiza automáticamente.

Además, ten en cuenta que en la barra de administración se ha agregado un enlace **Subdisciplinas** junto a **Disciplinas**, de modo que puedas gestionar cada catálogo desde el menú sin tener que navegar manualmente.

## Subida de logo de empresa

El campo `logo` en la tabla de empresas ya existe. Para manejar un archivo real:

1. **Formularios** (`views/empresas/crear.php` y `actualizar.php`): cambia el input a `type="file"` y añade `enctype="multipart/form-data"` en el `<form>`.
2. **Controlador** (`EmpresaController`): cuando llegue `$_FILES['logo']` debes:
   ```php
   if($_FILES['logo']['tmp_name']) {
       $nombreArchivo = md5(uniqid()) . '.' . pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
       $destino = __DIR__ . '/../public/image/' . $nombreArchivo;
       move_uploaded_file($_FILES['logo']['tmp_name'], $destino);
       // en la base de datos sólo guardamos el nombre del archivo
       $empresa->logo = $nombreArchivo;
   }
   ```
   Asegúrate de crear la carpeta `public/image` y dar permisos.
3. **Visualización**: en la lista de empresas concatena `/image/` al nombre guardado:
      `<img src="/image/<?= $e->logo ?>" width="100">`.

> Por ahora el formulario permite subir cualquier tipo de archivo; se puede restringir a JPG/PNG usando la extensión y mime.

## Navegación de disciplinas y subdisciplinas

No había un enlace separado para subdisciplinas. Hemos añadido el enlace **Disciplinas** en la barra principal; al hacer clic entra al listado de disciplinas. Dentro de esa pantalla puedes acceder a subdisciplinas (puedes agregar allí un botón o enlace para "Ver/Crear subdisciplinas" si lo deseas).

Para añadir accesos directos a subdisciplinas en la barra, edita `views/layout.php` y coloca otro `<a href="/subdisciplinas">Subdisciplinas</a>` junto a Disciplinas.

---

Después de estos ajustes podrás:
- Subir logos como archivos reales.
- Ver estilos similares al repositorio de referencia.
- Alternar el `estatus` de los clientes entre *activo* e *inactivo* desde el formulario.

Si quieres que implemente los enlaces dentro de la interfaz de disciplinas o mostrar logos en la tabla, házmelo saber y ajustamos también. ¡Vamos avanzando muy bien!"}