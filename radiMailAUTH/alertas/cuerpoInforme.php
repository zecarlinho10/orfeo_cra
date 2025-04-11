<?php

$cuerpo = "<!DOCTYPE html>
        <html><head><style>
            table {
                border-collapse: collapse;
                width: 100%;
            }

            th, td {
                border: 1px solid #dddddd;
                text-align: left;
                padding: 8px;
            }

            tr:nth-child(even) {
                background-color: #f2f2f2;
            }

            th {
                background-color: #4CAF50;
                color: white;
            }

            td.negative {
                color: red;
            }
            </style>
            </head>
            <body><h1>¡Informe radicados vencidos o a menos de 3 días por vencer en su dependencia!</h1>
            <p>Con el propósito de mantener un seguimiento adecuado sobe los radicados, este correo tiene como objetivo generar alertas sobre aquellos radicados próximos a vencer (Con plazo menor o igual a 3 dias).</p>
            <table>"; 

$cuerpo .= "<thead><tr><th>Ítem</th><th>Radicado</th><th>Fecha de Radicación</th><th>Fecha de Vencimiento</th><th>Días Hábiles para Vencimiento</th><th>Asunto</th><th>Usuario Actual</th></tr></thead><tbody>";
?>