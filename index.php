<?php
// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar si la URL ha sido ingresada
    if (isset($_POST['url'])) {
        // Obtener la URL ingresada por el usuario
        $url = $_POST['url'];

        // Asegurarse de que la URL tenga el protocolo adecuado
        if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
            $url = "http://" . $url;
        }

        // Obtener el contenido de la página web
        $html = file_get_contents($url);

        // Verificar si se obtuvo el contenido correctamente
        if ($html !== false) {
            // Extraer las etiquetas meta con las palabras clave
            $keywords = get_meta_keywords($html);

            // Mostrar las palabras clave
            echo "Palabras clave encontradas: " . implode(', ', $keywords);
        } else {
            echo "No se pudo acceder al contenido de la URL.";
        }
    }
}

/**
 * Función para obtener las palabras clave de las etiquetas meta.
 *
 * @param string $html El contenido HTML de la página web.
 * @return array Un array con las palabras clave encontradas.
 */
function get_meta_keywords($html) {
    $keywords = array();

    // Buscar la etiqueta meta con el atributo "name" igual a "keywords"
    preg_match('/<meta\s+name=["\']keywords["\']\s+content=["\'](.*?)["\']\s*\/?>/i', $html, $matches);

    if (isset($matches[1])) {
        // Separar las palabras clave usando comas como delimitador
        $keywords = explode(',', $matches[1]);

        // Limpiar espacios en blanco alrededor de las palabras clave
        $keywords = array_map('trim', $keywords);
    }

    return $keywords;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Obtener palabras clave de una página web</title>
</head>
<body>
    <h1>Obtener palabras clave de una página web</h1>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="url">Ingrese la URL de la página web:</label><br>
        <input type="text" id="url" name="url" placeholder="https://www.ejemplo.com" required><br><br>
        <input type="submit" value="Obtener palabras clave">
    </form>
</body>
</html>
