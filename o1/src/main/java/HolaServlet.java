package com.aprendo.haciendo.dwes;

import jakarta.servlet.annotation.WebServlet;
import jakarta.servlet.http.HttpServlet;
import jakarta.servlet.http.HttpServletRequest;
import jakarta.servlet.http.HttpServletResponse;
import java.io.IOException;
import java.io.PrintWriter;

// Esto le dice al servidor: "Cuando alguien pida la ruta /hola, ejecuta este código"
@WebServlet("/hola")
public class HolaServlet extends HttpServlet {

    // Sobrescribimos el método doGet para atender las peticiones HTTP GET
    @Override
    protected void doGet(HttpServletRequest request, HttpServletResponse response) throws IOException {
        
        // 1. Preparamos las cabeceras de la respuesta HTTP
        response.setContentType("text/html;charset=UTF-8");
        
        // 2. Escribimos el cuerpo de la respuesta (el HTML que verá el cliente)
        PrintWriter out = response.getWriter();
        out.println("<html>");
        out.println("<body>");
        out.println("<h1>¡Hola desde el servidor!</h1>");
        out.println("<p>Este HTML no estaba en un archivo, ¡lo acaba de generar Java!</p>");
        out.println("</body>");
        out.println("</html>");
    }
}

