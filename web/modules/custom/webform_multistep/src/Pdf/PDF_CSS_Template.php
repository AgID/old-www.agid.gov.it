<?php
  header("Content-type: text/css; charset: UTF-8");
?>
html {
  margin: 0;
}

@page {
  margin: 100px 2em 100px 2em;
}

footer, header {
  position: fixed;
  background-color: #0059b3;
  padding: 0.5em;
  left: 0;
  margin-left: -2em;
  margin-right: -2em;
  width: 100%;
  color: white;
  height: 50px;
}

header { top: -100px; }
footer { bottom: -100px; }

p + p + p { page-break-after: always; }

table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
  max-width: 100%;
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}

tr:nth-child(even) {
  background-color: #dddddd;
}
