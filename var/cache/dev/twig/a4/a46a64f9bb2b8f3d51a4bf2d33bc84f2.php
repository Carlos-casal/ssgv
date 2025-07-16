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

/* service/edit_service.html.twig */
class __TwigTemplate_96d062f5df1f28c5a6756ce0fa91b412 extends Template
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
        // line 3
        return "layout/app.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "service/edit_service.html.twig"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "service/edit_service.html.twig"));

        $this->parent = $this->load("layout/app.html.twig", 3);
        yield from $this->parent->unwrap()->yield($context, array_merge($this->blocks, $blocks));
        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    // line 5
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

        yield "Editar Servicio";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        yield from [];
    }

    // line 7
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

        // line 8
        yield "
    <div class=\"container mx-auto p-6\">
        <h1 class=\"text-2xl font-bold mb-6 text-gray-900\">Editar Servicio</h1>

        ";
        // line 13
        yield "        ";
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, (isset($context["app"]) || array_key_exists("app", $context) ? $context["app"] : (function () { throw new RuntimeError('Variable "app" does not exist.', 13, $this->source); })()), "flashes", ["success"], "method", false, false, false, 13));
        foreach ($context['_seq'] as $context["_key"] => $context["message"]) {
            // line 14
            yield "            <div class=\"bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4\" role=\"alert\">
                <span class=\"block sm:inline\">";
            // line 15
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($context["message"], "html", null, true);
            yield "</span>
            </div>
        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['message'], $context['_parent']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 18
        yield "        ";
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, (isset($context["app"]) || array_key_exists("app", $context) ? $context["app"] : (function () { throw new RuntimeError('Variable "app" does not exist.', 18, $this->source); })()), "flashes", ["error"], "method", false, false, false, 18));
        foreach ($context['_seq'] as $context["_key"] => $context["message"]) {
            // line 19
            yield "            <div class=\"bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4\" role=\"alert\">
                <span class=\"block sm:inline\">";
            // line 20
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($context["message"], "html", null, true);
            yield "</span>
            </div>
        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['message'], $context['_parent']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 23
        yield "
        <ul class=\"nav nav-tabs\" id=\"myTab\" role=\"tablist\">
            <li class=\"nav-item\">
                <a class=\"nav-link active\" id=\"datos_basicos-tab\" data-toggle=\"tab\" href=\"#datos_basicos\" role=\"tab\" aria-controls=\"datos_basicos\"
                aria-selected=\"true\"><i class=\"fas fa-file-signature\"></i> Datos básicos</a>
            </li>
            <li class=\"nav-item\">
                <a class=\"nav-link\" id=\"asistencias-tab\" data-toggle=\"tab\" href=\"#asistencias\" role=\"tab\" aria-controls=\"asistencias\"
                aria-selected=\"false\"><i class=\"fas fa-users\"></i> Confirmaciones de asistencia</a>
            </li>
        </ul>
        <div class=\"tab-content\" id=\"myTabContent\">
            <div class=\"tab-pane fade show active\" id=\"datos_basicos\" role=\"tabpanel\" aria-labelledby=\"datos_basicos-tab\">
                ";
        // line 36
        yield         $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->renderBlock((isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 36, $this->source); })()), 'form_start', ["attr" => ["class" => "bg-white p-6 rounded-lg shadow-md"], "enctype" => "multipart/form-data"]);
        yield "

                <div class=\"grid grid-cols-1 md:grid-cols-3 gap-6\">
                    <div class=\"col-span-1\">
                        <h3 class=\"text-lg font-medium text-gray-900 mb-4 border-b pb-2\">Datos del Servicio</h3>
                        <div class=\"mb-4\">
                            ";
        // line 42
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 42, $this->source); })()), "title", [], "any", false, false, false, 42), 'row', ["label_attr" => ["class" => "block text-sm font-medium text-gray-700"]]);
        yield "
                        </div>
                        <div class=\"mb-4\">
                            ";
        // line 45
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 45, $this->source); })()), "numeration", [], "any", false, false, false, 45), 'row', ["label_attr" => ["class" => "block text-sm font-medium text-gray-700"]]);
        yield "
                        </div>
                        <div class=\"mb-4\">
                            ";
        // line 48
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 48, $this->source); })()), "startDate", [], "any", false, false, false, 48), 'row', ["label_attr" => ["class" => "block text-sm font-medium text-gray-700"]]);
        yield "
                        </div>
                        <div class=\"mb-4\">
                            ";
        // line 51
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 51, $this->source); })()), "endDate", [], "any", false, false, false, 51), 'row', ["label_attr" => ["class" => "block text-sm font-medium text-gray-700"]]);
        yield "
                        </div>
                        <div class=\"mb-4\">
                            ";
        // line 54
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 54, $this->source); })()), "maxAttendees", [], "any", false, false, false, 54), 'row', ["label_attr" => ["class" => "block text-sm font-medium text-gray-700"]]);
        yield "
                        </div>
                        <div class=\"mb-4\">
                            ";
        // line 57
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 57, $this->source); })()), "type", [], "any", false, false, false, 57), 'row', ["label_attr" => ["class" => "block text-sm font-medium text-gray-700"]]);
        yield "
                        </div>
                        <div class=\"mb-4\">
                            ";
        // line 60
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 60, $this->source); })()), "category", [], "any", false, false, false, 60), 'row', ["label_attr" => ["class" => "block text-sm font-medium text-gray-700"]]);
        yield "
                        </div>
                        <div class=\"mb-4\">
                            ";
        // line 63
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 63, $this->source); })()), "description", [], "any", false, false, false, 63), 'row', ["label_attr" => ["class" => "block text-sm font-medium text-gray-700"]]);
        yield "
                        </div>
                        <div class=\"mb-4\">
                            ";
        // line 66
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 66, $this->source); })()), "recipients", [], "any", false, false, false, 66), 'row', ["label_attr" => ["class" => "block text-sm font-medium text-gray-700"]]);
        yield "
                        </div>
                    </div>
                </div>

                <div class=\"mt-6 text-center\">
                    <button type=\"submit\" class=\"bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300 ease-in-out\">
                        Guardar Cambios
                    </button>
                </div>

                ";
        // line 77
        yield         $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->renderBlock((isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 77, $this->source); })()), 'form_end');
        yield "
            </div>
            <div class=\"tab-pane fade\" id=\"asistencias\" role=\"tabpanel\" aria-labelledby=\"asistencias-tab\">
                <div class=\"row\">
                    <div class=\"col-lg-12\" style=\"padding-left:2em;\">
                        <button type=\"button\" class=\"pcam-btn-gris\" id=\"servicios_btn_add_respuesta_voluntario\" onclick=\"servicios_asistentes_add_open_modal()\"><i class=\"fas fa-plus-square\"></i> Añadir usuarios</button>
                        <button type=\"button\" class=\"pcam-btn-gris\" id=\"servicios_btn_fichar_todos\" onclick=\"servicios_fichar_todos_open_modal()\"><i class=\"fas fa-clock\"></i> Fichar a todos</button>
                    </div>
                </div>

                <div class=\"row\" style=\"padding: 0em 1em 0em 1em !important;\">
                    <div class=\"col-lg-4\" style=\"margin-top:1em;\">
                    <button type=\"button\" class=\"list-group-item list-group-item-action active\">Asisten <span class=\"badge badge-light\" id=\"n_asistentes\"></span></button>
                        <ul class=\"list-group\" id=\"servicio-listado-asistentes\">

                        </ul>
                    </div>

                    <div class=\"col-lg-4\" style=\"margin-top:1em;\">
                    <button type=\"button\" class=\"list-group-item list-group-item-action active\">Reserva <span class=\"badge badge-light\" id=\"n_reserva\"></span></button>
                    <ul class=\"list-group\" id=\"servicio-listado-reserva\">

                    </ul>
                    </div>

                    <div class=\"col-lg-4\" style=\"margin-top:1em;\">
                    <button type=\"button\" class=\"list-group-item list-group-item-action active\">No asisten <span class=\"badge badge-light\" id=\"n_no_asistentes\"></span></button>
                    <ul class=\"list-group\" id=\"servicio-listado-no-asistentes\">

                    </ul>
                    </div>

                </div>
            </div>
        </div>
    </div>

";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        yield from [];
    }

    // line 116
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

        // line 117
        yield "    ";
        yield from $this->yieldParentBlock("javascripts", $context, $blocks);
        yield " ";
        // line 118
        yield "
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabsContainer = document.getElementById('myTab');
            const tabContents = document.getElementById('myTabContent');

            if (tabsContainer && tabContents) {
                tabsContainer.addEventListener('click', function(event) {
                    const clickedButton = event.target.closest('[role=\"tab\"]'); // Encuentra el botón de la pestaña clickeado
                    if (!clickedButton) {
                        return; // No es un botón de pestaña, salimos
                    }

                    const targetId = clickedButton.dataset.tabsTarget; // Obtiene el ID del contenido objetivo (ej. '#basic-data')
                    const targetContent = tabContents.querySelector(targetId);

                    if (!targetContent) {
                        console.error('Contenido de pestaña no encontrado para:', targetId);
                        return;
                    }

                    // 1. Ocultar todos los contenidos de las pestañas
                    tabContents.querySelectorAll('[role=\"tabpanel\"]').forEach(panel => {
                        panel.classList.add('hidden');
                        panel.setAttribute('aria-hidden', 'true'); // Mejor accesibilidad
                    });

                    // 2. Desactivar visualmente todas las pestañas
                    tabsContainer.querySelectorAll('[role=\"tab\"]').forEach(tabButton => {
                        tabButton.setAttribute('aria-selected', 'false');
                        // Ajusta las clases para el estilo de pestaña inactiva (ej. borde gris, texto gris)
                        tabButton.classList.remove('text-blue-600', 'border-blue-600'); // Quita estilos de activa
                        tabButton.classList.add('text-gray-500', 'border-transparent', 'hover:text-gray-600', 'hover:border-gray-300'); // Añade estilos de inactiva
                    });

                    // 3. Mostrar el contenido de la pestaña clickeada
                    targetContent.classList.remove('hidden');
                    targetContent.setAttribute('aria-hidden', 'false');

                    // 4. Activar visualmente la pestaña clickeada
                    clickedButton.setAttribute('aria-selected', 'true');
                    // Ajusta las clases para el estilo de pestaña activa (ej. borde azul, texto azul)
                    clickedButton.classList.remove('text-gray-500', 'border-transparent', 'hover:text-gray-600', 'hover:border-gray-300'); // Quita estilos de inactiva
                    clickedButton.classList.add('text-blue-600', 'border-blue-600'); // Añade estilos de activa
                });

                // Opcional: Activar la primera pestaña por defecto al cargar
                // Esto simula un click en la primera pestaña
                const initialTab = tabsContainer.querySelector('[role=\"tab\"][aria-selected=\"true\"]') || tabsContainer.querySelector('[role=\"tab\"]');
                if(initialTab) {
                    initialTab.click();
                } else {
                    // Si no hay ninguna pestaña seleccionada por defecto, muestra el primer panel
                    const firstPanel = tabContents.querySelector('[role=\"tabpanel\"]');
                    if (firstPanel) {
                        firstPanel.classList.remove('hidden');
                        firstPanel.setAttribute('aria-hidden', 'false');
                    }
                }
            }
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
        return "service/edit_service.html.twig";
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
        return array (  293 => 118,  289 => 117,  276 => 116,  227 => 77,  213 => 66,  207 => 63,  201 => 60,  195 => 57,  189 => 54,  183 => 51,  177 => 48,  171 => 45,  165 => 42,  156 => 36,  141 => 23,  132 => 20,  129 => 19,  124 => 18,  115 => 15,  112 => 14,  107 => 13,  101 => 8,  88 => 7,  65 => 5,  42 => 3,);
    }

    public function getSourceContext(): Source
    {
        return new Source("{# templates/service/edit_service.html.twig #}

{% extends 'layout/app.html.twig' %} {# Asegúrate de que tu layout base es 'layout/app.html.twig' #}

{% block page_title %}Editar Servicio{% endblock %}

{% block content %}

    <div class=\"container mx-auto p-6\">
        <h1 class=\"text-2xl font-bold mb-6 text-gray-900\">Editar Servicio</h1>

        {# Mensajes flash #}
        {% for message in app.flashes('success') %}
            <div class=\"bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4\" role=\"alert\">
                <span class=\"block sm:inline\">{{ message }}</span>
            </div>
        {% endfor %}
        {% for message in app.flashes('error') %}
            <div class=\"bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4\" role=\"alert\">
                <span class=\"block sm:inline\">{{ message }}</span>
            </div>
        {% endfor %}

        <ul class=\"nav nav-tabs\" id=\"myTab\" role=\"tablist\">
            <li class=\"nav-item\">
                <a class=\"nav-link active\" id=\"datos_basicos-tab\" data-toggle=\"tab\" href=\"#datos_basicos\" role=\"tab\" aria-controls=\"datos_basicos\"
                aria-selected=\"true\"><i class=\"fas fa-file-signature\"></i> Datos básicos</a>
            </li>
            <li class=\"nav-item\">
                <a class=\"nav-link\" id=\"asistencias-tab\" data-toggle=\"tab\" href=\"#asistencias\" role=\"tab\" aria-controls=\"asistencias\"
                aria-selected=\"false\"><i class=\"fas fa-users\"></i> Confirmaciones de asistencia</a>
            </li>
        </ul>
        <div class=\"tab-content\" id=\"myTabContent\">
            <div class=\"tab-pane fade show active\" id=\"datos_basicos\" role=\"tabpanel\" aria-labelledby=\"datos_basicos-tab\">
                {{ form_start(form, {'attr': {'class': 'bg-white p-6 rounded-lg shadow-md'}, 'enctype': 'multipart/form-data'}) }}

                <div class=\"grid grid-cols-1 md:grid-cols-3 gap-6\">
                    <div class=\"col-span-1\">
                        <h3 class=\"text-lg font-medium text-gray-900 mb-4 border-b pb-2\">Datos del Servicio</h3>
                        <div class=\"mb-4\">
                            {{ form_row(form.title, {'label_attr': {'class': 'block text-sm font-medium text-gray-700'}}) }}
                        </div>
                        <div class=\"mb-4\">
                            {{ form_row(form.numeration, {'label_attr': {'class': 'block text-sm font-medium text-gray-700'}}) }}
                        </div>
                        <div class=\"mb-4\">
                            {{ form_row(form.startDate, {'label_attr': {'class': 'block text-sm font-medium text-gray-700'}}) }}
                        </div>
                        <div class=\"mb-4\">
                            {{ form_row(form.endDate, {'label_attr': {'class': 'block text-sm font-medium text-gray-700'}}) }}
                        </div>
                        <div class=\"mb-4\">
                            {{ form_row(form.maxAttendees, {'label_attr': {'class': 'block text-sm font-medium text-gray-700'}}) }}
                        </div>
                        <div class=\"mb-4\">
                            {{ form_row(form.type, {'label_attr': {'class': 'block text-sm font-medium text-gray-700'}}) }}
                        </div>
                        <div class=\"mb-4\">
                            {{ form_row(form.category, {'label_attr': {'class': 'block text-sm font-medium text-gray-700'}}) }}
                        </div>
                        <div class=\"mb-4\">
                            {{ form_row(form.description, {'label_attr': {'class': 'block text-sm font-medium text-gray-700'}}) }}
                        </div>
                        <div class=\"mb-4\">
                            {{ form_row(form.recipients, {'label_attr': {'class': 'block text-sm font-medium text-gray-700'}}) }}
                        </div>
                    </div>
                </div>

                <div class=\"mt-6 text-center\">
                    <button type=\"submit\" class=\"bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300 ease-in-out\">
                        Guardar Cambios
                    </button>
                </div>

                {{ form_end(form) }}
            </div>
            <div class=\"tab-pane fade\" id=\"asistencias\" role=\"tabpanel\" aria-labelledby=\"asistencias-tab\">
                <div class=\"row\">
                    <div class=\"col-lg-12\" style=\"padding-left:2em;\">
                        <button type=\"button\" class=\"pcam-btn-gris\" id=\"servicios_btn_add_respuesta_voluntario\" onclick=\"servicios_asistentes_add_open_modal()\"><i class=\"fas fa-plus-square\"></i> Añadir usuarios</button>
                        <button type=\"button\" class=\"pcam-btn-gris\" id=\"servicios_btn_fichar_todos\" onclick=\"servicios_fichar_todos_open_modal()\"><i class=\"fas fa-clock\"></i> Fichar a todos</button>
                    </div>
                </div>

                <div class=\"row\" style=\"padding: 0em 1em 0em 1em !important;\">
                    <div class=\"col-lg-4\" style=\"margin-top:1em;\">
                    <button type=\"button\" class=\"list-group-item list-group-item-action active\">Asisten <span class=\"badge badge-light\" id=\"n_asistentes\"></span></button>
                        <ul class=\"list-group\" id=\"servicio-listado-asistentes\">

                        </ul>
                    </div>

                    <div class=\"col-lg-4\" style=\"margin-top:1em;\">
                    <button type=\"button\" class=\"list-group-item list-group-item-action active\">Reserva <span class=\"badge badge-light\" id=\"n_reserva\"></span></button>
                    <ul class=\"list-group\" id=\"servicio-listado-reserva\">

                    </ul>
                    </div>

                    <div class=\"col-lg-4\" style=\"margin-top:1em;\">
                    <button type=\"button\" class=\"list-group-item list-group-item-action active\">No asisten <span class=\"badge badge-light\" id=\"n_no_asistentes\"></span></button>
                    <ul class=\"list-group\" id=\"servicio-listado-no-asistentes\">

                    </ul>
                    </div>

                </div>
            </div>
        </div>
    </div>

{% endblock %}

{% block javascripts %}
    {{ parent() }} {# Mantiene cualquier JS que venga del layout padre #}

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabsContainer = document.getElementById('myTab');
            const tabContents = document.getElementById('myTabContent');

            if (tabsContainer && tabContents) {
                tabsContainer.addEventListener('click', function(event) {
                    const clickedButton = event.target.closest('[role=\"tab\"]'); // Encuentra el botón de la pestaña clickeado
                    if (!clickedButton) {
                        return; // No es un botón de pestaña, salimos
                    }

                    const targetId = clickedButton.dataset.tabsTarget; // Obtiene el ID del contenido objetivo (ej. '#basic-data')
                    const targetContent = tabContents.querySelector(targetId);

                    if (!targetContent) {
                        console.error('Contenido de pestaña no encontrado para:', targetId);
                        return;
                    }

                    // 1. Ocultar todos los contenidos de las pestañas
                    tabContents.querySelectorAll('[role=\"tabpanel\"]').forEach(panel => {
                        panel.classList.add('hidden');
                        panel.setAttribute('aria-hidden', 'true'); // Mejor accesibilidad
                    });

                    // 2. Desactivar visualmente todas las pestañas
                    tabsContainer.querySelectorAll('[role=\"tab\"]').forEach(tabButton => {
                        tabButton.setAttribute('aria-selected', 'false');
                        // Ajusta las clases para el estilo de pestaña inactiva (ej. borde gris, texto gris)
                        tabButton.classList.remove('text-blue-600', 'border-blue-600'); // Quita estilos de activa
                        tabButton.classList.add('text-gray-500', 'border-transparent', 'hover:text-gray-600', 'hover:border-gray-300'); // Añade estilos de inactiva
                    });

                    // 3. Mostrar el contenido de la pestaña clickeada
                    targetContent.classList.remove('hidden');
                    targetContent.setAttribute('aria-hidden', 'false');

                    // 4. Activar visualmente la pestaña clickeada
                    clickedButton.setAttribute('aria-selected', 'true');
                    // Ajusta las clases para el estilo de pestaña activa (ej. borde azul, texto azul)
                    clickedButton.classList.remove('text-gray-500', 'border-transparent', 'hover:text-gray-600', 'hover:border-gray-300'); // Quita estilos de inactiva
                    clickedButton.classList.add('text-blue-600', 'border-blue-600'); // Añade estilos de activa
                });

                // Opcional: Activar la primera pestaña por defecto al cargar
                // Esto simula un click en la primera pestaña
                const initialTab = tabsContainer.querySelector('[role=\"tab\"][aria-selected=\"true\"]') || tabsContainer.querySelector('[role=\"tab\"]');
                if(initialTab) {
                    initialTab.click();
                } else {
                    // Si no hay ninguna pestaña seleccionada por defecto, muestra el primer panel
                    const firstPanel = tabContents.querySelector('[role=\"tabpanel\"]');
                    if (firstPanel) {
                        firstPanel.classList.remove('hidden');
                        firstPanel.setAttribute('aria-hidden', 'false');
                    }
                }
            }
        });
    </script>
{% endblock %}", "service/edit_service.html.twig", "C:\\xampp\\htdocs\\gesion_volunratios\\templates\\service\\edit_service.html.twig");
    }
}
