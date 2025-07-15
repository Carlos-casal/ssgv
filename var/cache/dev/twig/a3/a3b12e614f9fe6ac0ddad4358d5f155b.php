<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\CoreExtension;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;
use Twig\TemplateWrapper;

/* report/index.html.twig */
class __TwigTemplate_6374cb98f5188233b4a355f65bb28fe1 extends Template
{
    private Source $source;
    /**
     * @var array<string, Template>
     */
    private array $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->blocks = [
            'page_title' => [$this, 'block_page_title'],
            'content' => [$this, 'block_content'],
            'javascripts' => [$this, 'block_javascripts'],
        ];
    }

    protected function doGetParent(array $context): bool|string|Template|TemplateWrapper
    {
        // line 2
        return "layout/app.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "report/index.html.twig"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "report/index.html.twig"));

        $this->parent = $this->load("layout/app.html.twig", 2);
        yield from $this->parent->unwrap()->yield($context, array_merge($this->blocks, $blocks));
        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    // line 4
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_page_title(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "page_title"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "page_title"));

        yield "Informes y Estadísticas";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        yield from [];
    }

    // line 6
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_content(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "content"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "content"));

        // line 7
        yield "<div class=\"p-6 space-y-6\">
    <div class=\"flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4\">
        <div>
            <h2 class=\"text-2xl font-bold text-gray-900\">Panel de Informes y Estadísticas</h2>
            <p class=\"text-gray-600\">Visualiza datos clave sobre voluntarios y servicios de Protección Civil.</p>
        </div>
        ";
        // line 14
        yield "    </div>

    <div class=\"bg-white rounded-xl p-6 shadow-sm border border-gray-200\">
        <div class=\"mb-6\">
            <h3 class=\"text-lg font-semibold text-gray-800 mb-4\">Filtros de Informe</h3>
            <form id=\"form-filtros-informes\" class=\"grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4\">
                <div class=\"flex flex-col\">
                    <label for=\"fecha_inicio_informe\" class=\"block text-sm font-medium text-gray-700\">Fecha Inicio:</label>
                    <input type=\"date\" id=\"fecha_inicio_informe\" name=\"fecha_inicio\" class=\"mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500\">
                </div>
                <div class=\"flex flex-col\">
                    <label for=\"fecha_fin_informe\" class=\"block text-sm font-medium text-gray-700\">Fecha Fin:</label>
                    <input type=\"date\" id=\"fecha_fin_informe\" name=\"fecha_fin\" class=\"mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500\">
                </div>
                <div class=\"flex items-end\"> ";
        // line 29
        yield "                    <button type=\"button\" id=\"aplicar-filtros-informe\" class=\"w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition-colors\">
                        Aplicar Filtros
                    </button>
                </div>
            </form>
        </div>

        <div class=\"grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6\" id=\"estadisticas-cifras\">
            ";
        // line 38
        yield "            <div class=\"bg-blue-50 p-4 rounded-lg shadow-sm flex items-center justify-between\">
                <div>
                    <h4 class=\"text-sm font-medium text-blue-700\">Total Servicios</h4>
                    <p class=\"text-2xl font-bold text-blue-900\" id=\"total-servicios\">0</p>
                </div>
                <i data-lucide=\"bell\" class=\"w-8 h-8 text-blue-400\"></i> ";
        // line 44
        yield "            </div>
            <div class=\"bg-green-50 p-4 rounded-lg shadow-sm flex items-center justify-between\">
                <div>
                    <h4 class=\"text-sm font-medium text-green-700\">Voluntarios Activos</h4>
                    <p class=\"text-2xl font-bold text-green-900\" id=\"voluntarios-activos\">0</p>
                </div>
                <i data-lucide=\"users\" class=\"w-8 h-8 text-green-400\"></i>
            </div>
            <div class=\"bg-yellow-50 p-4 rounded-lg shadow-sm flex items-center justify-between\">
                <div>
                    <h4 class=\"text-sm font-medium text-yellow-700\">Servicios Pendientes</h4>
                    <p class=\"text-2xl font-bold text-yellow-900\" id=\"servicios-pendientes\">0</p>
                </div>
                <i data-lucide=\"clock\" class=\"w-8 h-8 text-yellow-400\"></i>
            </div>
            <div class=\"bg-red-50 p-4 rounded-lg shadow-sm flex items-center justify-between\">
                <div>
                    <h4 class=\"text-sm font-medium text-red-700\">Horas de Servicio</h4>
                    <p class=\"text-2xl font-bold text-red-900\" id=\"horas-servicio\">0</p>
                </div>
                <i data-lucide=\"hourglass\" class=\"w-8 h-8 text-red-400\"></i>
            </div>
        </div>

        <div class=\"grid grid-cols-1 lg:grid-cols-2 gap-6\">
            <div class=\"bg-white p-4 rounded-lg shadow-sm border border-gray-200\">
                <h3 class=\"text-lg font-semibold text-gray-800 mb-4\">Servicios por Categoría</h3>
                <canvas id=\"chart-servicios-categoria\"></canvas>
            </div>
            <div class=\"bg-white p-4 rounded-lg shadow-sm border border-gray-200\">
                <h3 class=\"text-lg font-semibold text-gray-800 mb-4\">Voluntarios por Rango de Edad</h3>
                <canvas id=\"chart-voluntarios-edad\"></canvas>
            </div>
        </div>

        <div class=\"bg-white p-4 rounded-lg shadow-sm border border-gray-200 mt-6\">
            <h3 class=\"text-lg font-semibold text-gray-800 mb-4\">Días desde el Último Servicio por Voluntario</h3>
            <div id=\"days-since-last-service-container\" class=\"overflow-x-auto\">
                <table class=\"min-w-full divide-y divide-gray-200\">
                    <thead class=\"bg-gray-50\">
                        <tr>
                            <th scope=\"col\" class=\"px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider\">Voluntario</th>
                            <th scope=\"col\" class=\"px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider\">Fecha Último Servicio</th>
                            <th scope=\"col\" class=\"px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider\">Días sin Servicio</th>
                        </tr>
                    </thead>
                    <tbody id=\"days-since-last-service-body\" class=\"bg-white divide-y divide-gray-200\">
                        ";
        // line 92
        yield "                        <tr><td colspan=\"3\" class=\"px-6 py-4 text-center text-gray-500\">Cargando datos...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        yield from [];
    }

    // line 102
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_javascripts(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "javascripts"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "javascripts"));

        // line 103
        yield "    ";
        yield from $this->yieldParentBlock("javascripts", $context, $blocks);
        yield " ";
        // line 104
        yield "
    ";
        // line 106
        yield "    ";
        // line 107
        yield "    <script src=\"https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js\"></script>
    <script src=\"https://cdn.jsdelivr.net/npm/chart.js\"></script>

    <script>
        \$(document).ready(function() {
            // Función para obtener y mostrar estadísticas y gráficos
            function cargarInformes() {
                const fechaInicio = \$('#fecha_inicio_informe').val();
                const fechaFin = \$('#fecha_fin_informe').val();

                // 1. AJAX para obtener estadísticas de cifras
                \$.ajax({
                    url: '";
        // line 119
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_volunteer_reports_api_stats");
        yield "',
                    method: 'GET',
                    data: {
                        fecha_inicio: fechaInicio,
                        fecha_fin: fechaFin
                    },
                    success: function(data) {
                        \$('#total-servicios').text(data.totalServicios || 0);
                        \$('#voluntarios-activos').text(data.voluntariosActivos || 0);
                        \$('#servicios-pendientes').text(data.serviciosPendientes || 0);
                        \$('#horas-servicio').text(data.horasServicio || 0);
                    },
                    error: function(xhr, status, error) {
                        console.error(\"Error al cargar estadísticas:\", error);
                    }
                });

                // 2. AJAX para Servicios por Categoría
                \$.ajax({
                    url: '";
        // line 138
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_volunteer_reports_api_services_by_category");
        yield "',
                    method: 'GET',
                    data: {
                        fecha_inicio: fechaInicio,
                        fecha_fin: fechaFin
                    },
                    success: function(data) {
                        renderServiciosCategoriaChart(data);
                    },
                    error: function(xhr, status, error) {
                        console.error(\"Error al cargar servicios por categoría:\", error);
                    }
                });

                // 3. AJAX para Voluntarios por Rango de Edad
                \$.ajax({
                    url: '";
        // line 154
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_volunteer_reports_api_volunteers_by_age");
        yield "',
                    method: 'GET',
                    data: {
                        fecha_inicio: fechaInicio,
                        fecha_fin: fechaFin
                    },
                    success: function(data) {
                        renderVoluntariosEdadChart(data);
                    },
                    error: function(xhr, status, error) {
                        console.error(\"Error al cargar voluntarios por edad:\", error);
                    }
                });

                // 4. NUEVA LLAMADA AJAX para Días desde el Último Servicio
                \$.ajax({
                    url: '";
        // line 170
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_volunteer_reports_api_days_since_last_service");
        yield "',
                    method: 'GET',
                    success: function(data) {
                        const tbody = \$('#days-since-last-service-body');
                        tbody.empty(); // Limpiar contenido anterior
                        if (data.length > 0) {
                            data.forEach(item => {
                                const row = `
                                    <tr>
                                        <td class=\"px-6 py-4 whitespace-nowrap\">\${item.volunteerName}</td>
                                        <td class=\"px-6 py-4 whitespace-nowrap\">\${item.lastServiceDate}</td>
                                        <td class=\"px-6 py-4 whitespace-nowrap\">\${item.daysSinceLastService}</td>
                                    </tr>
                                `;
                                tbody.append(row);
                            });
                        } else {
                            tbody.append('<tr><td colspan=\"3\" class=\"px-6 py-4 text-center text-gray-500\">No se encontraron datos.</td></tr>');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(\"Error al cargar días desde el último servicio:\", error);
                        \$('#days-since-last-service-body').empty().append('<tr><td colspan=\"3\" class=\"px-6 py-4 text-center text-red-500\">Error al cargar datos.</td></tr>');
                    }
                });
            }

            // Variable global para destruir el gráfico anterior si se actualiza
            let serviciosCategoriaChart;
            function renderServiciosCategoriaChart(chartData) {
                const ctx = document.getElementById('chart-servicios-categoria').getContext('2d');
                if (serviciosCategoriaChart) {
                    serviciosCategoriaChart.destroy();
                }
                serviciosCategoriaChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: chartData.labels,
                        datasets: [{
                            label: 'Número de Servicios',
                            data: chartData.data,
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.6)', 'rgba(54, 162, 235, 0.6)', 'rgba(255, 206, 86, 0.6)',
                                'rgba(75, 192, 192, 0.6)', 'rgba(153, 102, 255, 0.6)', 'rgba(255, 159, 64, 0.6)'
                            ],
                            borderColor: [
                                'rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)', 'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)', 'rgba(153, 102, 255, 1)', 'rgba(255, 159, 64, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });
            }

            let voluntariosEdadChart;
            function renderVoluntariosEdadChart(chartData) {
                const ctx = document.getElementById('chart-voluntarios-edad').getContext('2d');
                if (voluntariosEdadChart) {
                    voluntariosEdadChart.destroy();
                }
                voluntariosEdadChart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: chartData.labels,
                        datasets: [{
                            label: 'Número de Voluntarios',
                            data: chartData.data,
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.6)', 'rgba(54, 162, 235, 0.6)', 'rgba(255, 206, 86, 0.6)',
                                'rgba(75, 192, 192, 0.6)', 'rgba(153, 102, 255, 0.6)'
                            ],
                            borderColor: [
                                'rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)', 'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)', 'rgba(153, 102, 255, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top',
                            }
                        }
                    }
                });
            }

            // Event listener para el botón de aplicar filtros
            \$('#aplicar-filtros-informe').on('click', cargarInformes);

            // Cargar informes al cargar la página por primera vez
            cargarInformes();
        });
    </script>
";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "report/index.html.twig";
    }

    /**
     * @codeCoverageIgnore
     */
    public function isTraitable(): bool
    {
        return false;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDebugInfo(): array
    {
        return array (  306 => 170,  287 => 154,  268 => 138,  246 => 119,  232 => 107,  230 => 106,  227 => 104,  223 => 103,  210 => 102,  191 => 92,  142 => 44,  135 => 38,  125 => 29,  109 => 14,  101 => 7,  88 => 6,  65 => 4,  42 => 2,);
    }

    public function getSourceContext(): Source
    {
        return new Source("{# templates/report/index.html.twig #}
{% extends 'layout/app.html.twig' %}

{% block page_title %}Informes y Estadísticas{% endblock %}

{% block content %}
<div class=\"p-6 space-y-6\">
    <div class=\"flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4\">
        <div>
            <h2 class=\"text-2xl font-bold text-gray-900\">Panel de Informes y Estadísticas</h2>
            <p class=\"text-gray-600\">Visualiza datos clave sobre voluntarios y servicios de Protección Civil.</p>
        </div>
        {# Aquí podrías añadir botones de acción específicos para informes si los necesitas #}
    </div>

    <div class=\"bg-white rounded-xl p-6 shadow-sm border border-gray-200\">
        <div class=\"mb-6\">
            <h3 class=\"text-lg font-semibold text-gray-800 mb-4\">Filtros de Informe</h3>
            <form id=\"form-filtros-informes\" class=\"grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4\">
                <div class=\"flex flex-col\">
                    <label for=\"fecha_inicio_informe\" class=\"block text-sm font-medium text-gray-700\">Fecha Inicio:</label>
                    <input type=\"date\" id=\"fecha_inicio_informe\" name=\"fecha_inicio\" class=\"mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500\">
                </div>
                <div class=\"flex flex-col\">
                    <label for=\"fecha_fin_informe\" class=\"block text-sm font-medium text-gray-700\">Fecha Fin:</label>
                    <input type=\"date\" id=\"fecha_fin_informe\" name=\"fecha_fin\" class=\"mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500\">
                </div>
                <div class=\"flex items-end\"> {# Para alinear el botón con los inputs #}
                    <button type=\"button\" id=\"aplicar-filtros-informe\" class=\"w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition-colors\">
                        Aplicar Filtros
                    </button>
                </div>
            </form>
        </div>

        <div class=\"grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6\" id=\"estadisticas-cifras\">
            {# Estas tarjetas se poblarán con datos vía JavaScript #}
            <div class=\"bg-blue-50 p-4 rounded-lg shadow-sm flex items-center justify-between\">
                <div>
                    <h4 class=\"text-sm font-medium text-blue-700\">Total Servicios</h4>
                    <p class=\"text-2xl font-bold text-blue-900\" id=\"total-servicios\">0</p>
                </div>
                <i data-lucide=\"bell\" class=\"w-8 h-8 text-blue-400\"></i> {# Icono de ejemplo #}
            </div>
            <div class=\"bg-green-50 p-4 rounded-lg shadow-sm flex items-center justify-between\">
                <div>
                    <h4 class=\"text-sm font-medium text-green-700\">Voluntarios Activos</h4>
                    <p class=\"text-2xl font-bold text-green-900\" id=\"voluntarios-activos\">0</p>
                </div>
                <i data-lucide=\"users\" class=\"w-8 h-8 text-green-400\"></i>
            </div>
            <div class=\"bg-yellow-50 p-4 rounded-lg shadow-sm flex items-center justify-between\">
                <div>
                    <h4 class=\"text-sm font-medium text-yellow-700\">Servicios Pendientes</h4>
                    <p class=\"text-2xl font-bold text-yellow-900\" id=\"servicios-pendientes\">0</p>
                </div>
                <i data-lucide=\"clock\" class=\"w-8 h-8 text-yellow-400\"></i>
            </div>
            <div class=\"bg-red-50 p-4 rounded-lg shadow-sm flex items-center justify-between\">
                <div>
                    <h4 class=\"text-sm font-medium text-red-700\">Horas de Servicio</h4>
                    <p class=\"text-2xl font-bold text-red-900\" id=\"horas-servicio\">0</p>
                </div>
                <i data-lucide=\"hourglass\" class=\"w-8 h-8 text-red-400\"></i>
            </div>
        </div>

        <div class=\"grid grid-cols-1 lg:grid-cols-2 gap-6\">
            <div class=\"bg-white p-4 rounded-lg shadow-sm border border-gray-200\">
                <h3 class=\"text-lg font-semibold text-gray-800 mb-4\">Servicios por Categoría</h3>
                <canvas id=\"chart-servicios-categoria\"></canvas>
            </div>
            <div class=\"bg-white p-4 rounded-lg shadow-sm border border-gray-200\">
                <h3 class=\"text-lg font-semibold text-gray-800 mb-4\">Voluntarios por Rango de Edad</h3>
                <canvas id=\"chart-voluntarios-edad\"></canvas>
            </div>
        </div>

        <div class=\"bg-white p-4 rounded-lg shadow-sm border border-gray-200 mt-6\">
            <h3 class=\"text-lg font-semibold text-gray-800 mb-4\">Días desde el Último Servicio por Voluntario</h3>
            <div id=\"days-since-last-service-container\" class=\"overflow-x-auto\">
                <table class=\"min-w-full divide-y divide-gray-200\">
                    <thead class=\"bg-gray-50\">
                        <tr>
                            <th scope=\"col\" class=\"px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider\">Voluntario</th>
                            <th scope=\"col\" class=\"px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider\">Fecha Último Servicio</th>
                            <th scope=\"col\" class=\"px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider\">Días sin Servicio</th>
                        </tr>
                    </thead>
                    <tbody id=\"days-since-last-service-body\" class=\"bg-white divide-y divide-gray-200\">
                        {# Los datos se cargarán aquí con JavaScript #}
                        <tr><td colspan=\"3\" class=\"px-6 py-4 text-center text-gray-500\">Cargando datos...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
{% endblock %}

{% block javascripts %}
    {{ parent() }} {# Importante para mantener los scripts del layout base, como los de Lucide Icons #}

    {# Cargar librerías JS necesarias: jQuery y Chart.js #}
    {# Asegúrate de que jQuery no se cargue dos veces si ya está en tu app.html.twig #}
    <script src=\"https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js\"></script>
    <script src=\"https://cdn.jsdelivr.net/npm/chart.js\"></script>

    <script>
        \$(document).ready(function() {
            // Función para obtener y mostrar estadísticas y gráficos
            function cargarInformes() {
                const fechaInicio = \$('#fecha_inicio_informe').val();
                const fechaFin = \$('#fecha_fin_informe').val();

                // 1. AJAX para obtener estadísticas de cifras
                \$.ajax({
                    url: '{{ path(\"app_volunteer_reports_api_stats\") }}',
                    method: 'GET',
                    data: {
                        fecha_inicio: fechaInicio,
                        fecha_fin: fechaFin
                    },
                    success: function(data) {
                        \$('#total-servicios').text(data.totalServicios || 0);
                        \$('#voluntarios-activos').text(data.voluntariosActivos || 0);
                        \$('#servicios-pendientes').text(data.serviciosPendientes || 0);
                        \$('#horas-servicio').text(data.horasServicio || 0);
                    },
                    error: function(xhr, status, error) {
                        console.error(\"Error al cargar estadísticas:\", error);
                    }
                });

                // 2. AJAX para Servicios por Categoría
                \$.ajax({
                    url: '{{ path(\"app_volunteer_reports_api_services_by_category\") }}',
                    method: 'GET',
                    data: {
                        fecha_inicio: fechaInicio,
                        fecha_fin: fechaFin
                    },
                    success: function(data) {
                        renderServiciosCategoriaChart(data);
                    },
                    error: function(xhr, status, error) {
                        console.error(\"Error al cargar servicios por categoría:\", error);
                    }
                });

                // 3. AJAX para Voluntarios por Rango de Edad
                \$.ajax({
                    url: '{{ path(\"app_volunteer_reports_api_volunteers_by_age\") }}',
                    method: 'GET',
                    data: {
                        fecha_inicio: fechaInicio,
                        fecha_fin: fechaFin
                    },
                    success: function(data) {
                        renderVoluntariosEdadChart(data);
                    },
                    error: function(xhr, status, error) {
                        console.error(\"Error al cargar voluntarios por edad:\", error);
                    }
                });

                // 4. NUEVA LLAMADA AJAX para Días desde el Último Servicio
                \$.ajax({
                    url: '{{ path(\"app_volunteer_reports_api_days_since_last_service\") }}',
                    method: 'GET',
                    success: function(data) {
                        const tbody = \$('#days-since-last-service-body');
                        tbody.empty(); // Limpiar contenido anterior
                        if (data.length > 0) {
                            data.forEach(item => {
                                const row = `
                                    <tr>
                                        <td class=\"px-6 py-4 whitespace-nowrap\">\${item.volunteerName}</td>
                                        <td class=\"px-6 py-4 whitespace-nowrap\">\${item.lastServiceDate}</td>
                                        <td class=\"px-6 py-4 whitespace-nowrap\">\${item.daysSinceLastService}</td>
                                    </tr>
                                `;
                                tbody.append(row);
                            });
                        } else {
                            tbody.append('<tr><td colspan=\"3\" class=\"px-6 py-4 text-center text-gray-500\">No se encontraron datos.</td></tr>');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(\"Error al cargar días desde el último servicio:\", error);
                        \$('#days-since-last-service-body').empty().append('<tr><td colspan=\"3\" class=\"px-6 py-4 text-center text-red-500\">Error al cargar datos.</td></tr>');
                    }
                });
            }

            // Variable global para destruir el gráfico anterior si se actualiza
            let serviciosCategoriaChart;
            function renderServiciosCategoriaChart(chartData) {
                const ctx = document.getElementById('chart-servicios-categoria').getContext('2d');
                if (serviciosCategoriaChart) {
                    serviciosCategoriaChart.destroy();
                }
                serviciosCategoriaChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: chartData.labels,
                        datasets: [{
                            label: 'Número de Servicios',
                            data: chartData.data,
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.6)', 'rgba(54, 162, 235, 0.6)', 'rgba(255, 206, 86, 0.6)',
                                'rgba(75, 192, 192, 0.6)', 'rgba(153, 102, 255, 0.6)', 'rgba(255, 159, 64, 0.6)'
                            ],
                            borderColor: [
                                'rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)', 'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)', 'rgba(153, 102, 255, 1)', 'rgba(255, 159, 64, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });
            }

            let voluntariosEdadChart;
            function renderVoluntariosEdadChart(chartData) {
                const ctx = document.getElementById('chart-voluntarios-edad').getContext('2d');
                if (voluntariosEdadChart) {
                    voluntariosEdadChart.destroy();
                }
                voluntariosEdadChart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: chartData.labels,
                        datasets: [{
                            label: 'Número de Voluntarios',
                            data: chartData.data,
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.6)', 'rgba(54, 162, 235, 0.6)', 'rgba(255, 206, 86, 0.6)',
                                'rgba(75, 192, 192, 0.6)', 'rgba(153, 102, 255, 0.6)'
                            ],
                            borderColor: [
                                'rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)', 'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)', 'rgba(153, 102, 255, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top',
                            }
                        }
                    }
                });
            }

            // Event listener para el botón de aplicar filtros
            \$('#aplicar-filtros-informe').on('click', cargarInformes);

            // Cargar informes al cargar la página por primera vez
            cargarInformes();
        });
    </script>
{% endblock %}", "report/index.html.twig", "C:\\xampp\\htdocs\\gesion_volunratios\\templates\\report\\index.html.twig");
    }
}
