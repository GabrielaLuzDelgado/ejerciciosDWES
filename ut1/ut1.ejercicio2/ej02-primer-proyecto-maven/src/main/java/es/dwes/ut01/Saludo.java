package es.dwes.ut01;

public class Saludo {

    /**
     * Devuelve un saludo para el nombre indicado.
     * Ejemplo: saluda("Ana") devuelve "Hola, Ana"..
     * Si el nombre es null o está vacío, devuelve "Hola, desconocido".
     */
    public String saluda(String nombre) {
        if (nombre == null || nombre.isBlank()) {
            return "Hola, desconocido";
        }
        return "Hola, " + nombre.strip();
    }
}
