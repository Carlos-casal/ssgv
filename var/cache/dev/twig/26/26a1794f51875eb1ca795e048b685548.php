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

/* service/view.html.twig */
class __TwigTemplate_858e3ef6d3e979f8a860fe14928a28e4 extends Template
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
        ];
    }

    protected function doGetParent(array $context): bool|string|Template|TemplateWrapper
    {
        // line 1
        return "layout/app.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "service/view.html.twig"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "service/view.html.twig"));

        $this->parent = $this->load("layout/app.html.twig", 1);
        yield from $this->parent->unwrap()->yield($context, array_merge($this->blocks, $blocks));
        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    // line 3
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

        yield "Detalles del Servicio: ";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, (isset($context["service"]) || array_key_exists("service", $context) ? $context["service"] : (function () { throw new RuntimeError('Variable "service" does not exist.', 3, $this->source); })()), "title", [], "any", false, false, false, 3), "html", null, true);
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        yield from [];
    }

    // line 5
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

        // line 6
        yield "<div class=\"p-6\">
    <div class=\"flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4\">
        <div>
            <h2 class=\"text-2xl font-bold text-gray-900\">
                <a href=\"";
        // line 10
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_services_list");
        yield "\">Servicios</a> / ";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, (isset($context["service"]) || array_key_exists("service", $context) ? $context["service"] : (function () { throw new RuntimeError('Variable "service" does not exist.', 10, $this->source); })()), "title", [], "any", false, false, false, 10), "html", null, true);
        yield "
            </h2>
        </div>
        <div class=\"flex gap-3\">
            <a href=\"#\" class=\"flex items-center gap-2 px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors\">
                <i data-lucide=\"copy\" class=\"w-4 h-4\"></i>
                Duplicar
            </a>
            <a href=\"#\" class=\"flex items-center gap-2 px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors\">
                <i data-lucide=\"archive\" class=\"w-4 h-4\"></i>
                Archivar
            </a>
            <a href=\"#\" class=\"flex items-center gap-2 px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors\">
                <i data-lucide=\"printer\" class=\"w-4 h-4\"></i>
                Imprimir
            </a>
        </div>
    </div>

    <div class=\"mt-6\">
        <div class=\"border-b border-gray-200\">
            <nav class=\"-mb-px flex space-x-8\" aria-label=\"Tabs\">
                <a href=\"#datos_basicos\" class=\"tab-link active-tab border-blue-500 text-blue-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm\">
                    <i class=\"fas fa-file-signature\"></i> Datos básicos
                </a>
                <a href=\"#avanzado\" class=\"tab-link border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm\">
                    <i class=\"fas fa-file-signature\"></i> Avanzado
                </a>
                <a href=\"#asistencias\" class=\"tab-link border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm\">
                    <i class=\"fas fa-users\"></i> Confirmaciones de asistencia
                </a>
                <a href=\"#claves\" class=\"tab-link border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm\">
                    <i class=\"fas fa-comments\"></i> Claves
                </a>
                <a href=\"#intervenciones\" class=\"tab-link border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm\">
                    <i class=\"fas fa-user-injured\"></i> Intervenciones
                </a>
                <a href=\"#otros\" class=\"tab-link border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm\">
                    <i class=\"fas fa-file-signature\"></i> Otros
                </a>
                <a href=\"#materiales\" class=\"tab-link border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm\">
                    <i class=\"fas fa-boxes\"></i> Materiales
                </a>
                <a href=\"#vehiculos\" class=\"tab-link border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm\">
                    <i class=\"fas fa-truck\"></i> Vehículos
                </a>
                <a href=\"#incidencias\" class=\"tab-link border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm\">
                    <i class=\"fas fa-times\"></i> Incidencias
                </a>
                <a href=\"#comunicaciones\" class=\"tab-link border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm\">
                    <i class=\"fas fa-comments\"></i> Comunicaciones
                </a>
                <a href=\"#adjuntos\" class=\"tab-link border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm\">
                    <i class=\"fas fa-file-upload\"></i> Adjuntos
                </a>
            </nav>
        </div>

        <div class=\"mt-6\">
            ";
        // line 69
        yield         $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->renderBlock((isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 69, $this->source); })()), 'form_start', ["action" => $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_service_edit", ["id" => CoreExtension::getAttribute($this->env, $this->source, (isset($context["service"]) || array_key_exists("service", $context) ? $context["service"] : (function () { throw new RuntimeError('Variable "service" does not exist.', 69, $this->source); })()), "id", [], "any", false, false, false, 69)]), "method" => "POST"]);
        yield "
            <div id=\"datos_basicos\" class=\"tab-content active-content\">
                <div class=\"grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6\">
                    <div>
                        ";
        // line 73
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 73, $this->source); })()), "numeration", [], "any", false, false, false, 73), 'label', ["label" => "Numeración"]);
        yield "
                        ";
        // line 74
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 74, $this->source); })()), "numeration", [], "any", false, false, false, 74), 'widget', ["attr" => ["class" => "mt-1 block w-full rounded-md border-gray-300 shadow-sm"]]);
        yield "
                    </div>
                    <div class=\"lg:col-span-2\">
                        ";
        // line 77
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 77, $this->source); })()), "title", [], "any", false, false, false, 77), 'label', ["label" => "Título"]);
        yield "
                        ";
        // line 78
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 78, $this->source); })()), "title", [], "any", false, false, false, 78), 'widget', ["attr" => ["class" => "mt-1 block w-full rounded-md border-gray-300 shadow-sm"]]);
        yield "
                    </div>
                    <div></div>
                    <div>
                        ";
        // line 82
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 82, $this->source); })()), "startDate", [], "any", false, false, false, 82), 'label', ["label" => "Fecha y hora de inicio"]);
        yield "
                        ";
        // line 83
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 83, $this->source); })()), "startDate", [], "any", false, false, false, 83), 'widget', ["attr" => ["class" => "mt-1 block w-full rounded-md border-gray-300 shadow-sm"]]);
        yield "
                    </div>
                    <div>
                        ";
        // line 86
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 86, $this->source); })()), "endDate", [], "any", false, false, false, 86), 'label', ["label" => "Fecha y hora de finalización"]);
        yield "
                        ";
        // line 87
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 87, $this->source); })()), "endDate", [], "any", false, false, false, 87), 'widget', ["attr" => ["class" => "mt-1 block w-full rounded-md border-gray-300 shadow-sm"]]);
        yield "
                    </div>
                    <div>
                        ";
        // line 90
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 90, $this->source); })()), "registrationLimitDate", [], "any", false, false, false, 90), 'label', ["label" => "Límite de inscripción"]);
        yield "
                        ";
        // line 91
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 91, $this->source); })()), "registrationLimitDate", [], "any", false, false, false, 91), 'widget', ["attr" => ["class" => "mt-1 block w-full rounded-md border-gray-300 shadow-sm"]]);
        yield "
                    </div>
                    <div>
                        ";
        // line 94
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 94, $this->source); })()), "timeAtBase", [], "any", false, false, false, 94), 'label', ["label" => "Hora en base"]);
        yield "
                        ";
        // line 95
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 95, $this->source); })()), "timeAtBase", [], "any", false, false, false, 95), 'widget', ["attr" => ["class" => "mt-1 block w-full rounded-md border-gray-300 shadow-sm"]]);
        yield "
                    </div>
                    <div>
                        ";
        // line 98
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 98, $this->source); })()), "departureTime", [], "any", false, false, false, 98), 'label', ["label" => "Hora de salida"]);
        yield "
                        ";
        // line 99
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 99, $this->source); })()), "departureTime", [], "any", false, false, false, 99), 'widget', ["attr" => ["class" => "mt-1 block w-full rounded-md border-gray-300 shadow-sm"]]);
        yield "
                    </div>
                    <div>
                        ";
        // line 102
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 102, $this->source); })()), "maxAttendees", [], "any", false, false, false, 102), 'label', ["label" => "Máximo asistentes"]);
        yield "
                        ";
        // line 103
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 103, $this->source); })()), "maxAttendees", [], "any", false, false, false, 103), 'widget', ["attr" => ["class" => "mt-1 block w-full rounded-md border-gray-300 shadow-sm"]]);
        yield "
                    </div>
                    <div>
                        ";
        // line 106
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 106, $this->source); })()), "type", [], "any", false, false, false, 106), 'label', ["label" => "Tipo de servicio"]);
        yield "
                        ";
        // line 107
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 107, $this->source); })()), "type", [], "any", false, false, false, 107), 'widget', ["attr" => ["class" => "mt-1 block w-full rounded-md border-gray-300 shadow-sm"]]);
        yield "
                    </div>
                    <div>
                        ";
        // line 110
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 110, $this->source); })()), "category", [], "any", false, false, false, 110), 'label', ["label" => "Categoría"]);
        yield "
                        ";
        // line 111
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 111, $this->source); })()), "category", [], "any", false, false, false, 111), 'widget', ["attr" => ["class" => "mt-1 block w-full rounded-md border-gray-300 shadow-sm"]]);
        yield "
                    </div>
                </div>
                <div class=\"mt-6\">
                    ";
        // line 115
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 115, $this->source); })()), "description", [], "any", false, false, false, 115), 'label', ["label" => "Descripción"]);
        yield "
                    ";
        // line 116
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 116, $this->source); })()), "description", [], "any", false, false, false, 116), 'widget', ["attr" => ["class" => "mt-1 block w-full rounded-md border-gray-300 shadow-sm", "rows" => 10]]);
        yield "
                </div>
            </div>

            <div id=\"avanzado\" class=\"tab-content hidden\">
                <div class=\"grid grid-cols-1 md:grid-cols-2 gap-6\">
                    <div>
                        ";
        // line 123
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 123, $this->source); })()), "locality", [], "any", false, false, false, 123), 'label', ["label" => "Localidad"]);
        yield "
                        ";
        // line 124
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 124, $this->source); })()), "locality", [], "any", false, false, false, 124), 'widget', ["attr" => ["class" => "mt-1 block w-full rounded-md border-gray-300 shadow-sm"]]);
        yield "
                    </div>
                    <div>
                        ";
        // line 127
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 127, $this->source); })()), "requester", [], "any", false, false, false, 127), 'label', ["label" => "Solicitante"]);
        yield "
                        ";
        // line 128
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 128, $this->source); })()), "requester", [], "any", false, false, false, 128), 'widget', ["attr" => ["class" => "mt-1 block w-full rounded-md border-gray-300 shadow-sm"]]);
        yield "
                    </div>
                    <div class=\"md:col-span-2\">
                        ";
        // line 131
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 131, $this->source); })()), "collaboration_with_other_services", [], "any", false, false, false, 131), 'widget');
        yield "
                    </div>
                </div>
            </div>

            <div id=\"asistencias\" class=\"tab-content hidden\">
                <p>Confirmaciones de asistencia (contenido pendiente)</p>
            </div>
            <div id=\"claves\" class=\"tab-content hidden\">
                <p>Claves (contenido pendiente)</p>
            </div>
            <div id=\"intervenciones\" class=\"tab-content hidden\">
                <p>Intervenciones (contenido pendiente)</p>
            </div>
            <div id=\"otros\" class=\"tab-content hidden\">
                <p>Otros (contenido pendiente)</p>
            </div>
            <div id=\"materiales\" class=\"tab-content hidden\">
                <p>Materiales (contenido pendiente)</p>
            </div>
            <div id=\"vehiculos\" class=\"tab-content hidden\">
                <p>Vehículos (contenido pendiente)</p>
            </div>
            <div id=\"incidencias\" class=\"tab-content hidden\">
                <p>Incidencias (contenido pendiente)</p>
            </div>
            <div id=\"comunicaciones\" class=\"tab-content hidden\">
                <p>Comunicaciones (contenido pendiente)</p>
            </div>
            <div id=\"adjuntos\" class=\"tab-content hidden\">
                <p>Adjuntos (contenido pendiente)</p>
            </div>

            <div class=\"flex justify-end gap-3 pt-6 border-t border-gray-200 mt-8\">
                <a href=\"";
        // line 165
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_services_list");
        yield "\" class=\"px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50\">
                    Cancelar
                </a>
                <button type=\"submit\" class=\"px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700\">
                    Guardar Cambios
                </button>
            </div>
            ";
        // line 172
        yield         $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->renderBlock((isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 172, $this->source); })()), 'form_end');
        yield "
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tabs = document.querySelectorAll('.tab-link');
        const contents = document.querySelectorAll('.tab-content');

        tabs.forEach(tab => {
            tab.addEventListener('click', function (event) {
                event.preventDefault();

                tabs.forEach(t => {
                    t.classList.remove('active-tab', 'border-blue-500', 'text-blue-600');
                    t.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
                });

                this.classList.add('active-tab', 'border-blue-500', 'text-blue-600');
                this.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');

                contents.forEach(content => {
                    content.classList.add('hidden');
                });

                const activeContent = document.querySelector(this.getAttribute('href'));
                if (activeContent) {
                    activeContent.classList.remove('hidden');
                }
            });
        });
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
        return "service/view.html.twig";
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
        return array (  361 => 172,  351 => 165,  314 => 131,  308 => 128,  304 => 127,  298 => 124,  294 => 123,  284 => 116,  280 => 115,  273 => 111,  269 => 110,  263 => 107,  259 => 106,  253 => 103,  249 => 102,  243 => 99,  239 => 98,  233 => 95,  229 => 94,  223 => 91,  219 => 90,  213 => 87,  209 => 86,  203 => 83,  199 => 82,  192 => 78,  188 => 77,  182 => 74,  178 => 73,  171 => 69,  107 => 10,  101 => 6,  88 => 5,  64 => 3,  41 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("{% extends 'layout/app.html.twig' %}

{% block page_title %}Detalles del Servicio: {{ service.title }}{% endblock %}

{% block content %}
<div class=\"p-6\">
    <div class=\"flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4\">
        <div>
            <h2 class=\"text-2xl font-bold text-gray-900\">
                <a href=\"{{ path('app_services_list') }}\">Servicios</a> / {{ service.title }}
            </h2>
        </div>
        <div class=\"flex gap-3\">
            <a href=\"#\" class=\"flex items-center gap-2 px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors\">
                <i data-lucide=\"copy\" class=\"w-4 h-4\"></i>
                Duplicar
            </a>
            <a href=\"#\" class=\"flex items-center gap-2 px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors\">
                <i data-lucide=\"archive\" class=\"w-4 h-4\"></i>
                Archivar
            </a>
            <a href=\"#\" class=\"flex items-center gap-2 px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors\">
                <i data-lucide=\"printer\" class=\"w-4 h-4\"></i>
                Imprimir
            </a>
        </div>
    </div>

    <div class=\"mt-6\">
        <div class=\"border-b border-gray-200\">
            <nav class=\"-mb-px flex space-x-8\" aria-label=\"Tabs\">
                <a href=\"#datos_basicos\" class=\"tab-link active-tab border-blue-500 text-blue-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm\">
                    <i class=\"fas fa-file-signature\"></i> Datos básicos
                </a>
                <a href=\"#avanzado\" class=\"tab-link border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm\">
                    <i class=\"fas fa-file-signature\"></i> Avanzado
                </a>
                <a href=\"#asistencias\" class=\"tab-link border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm\">
                    <i class=\"fas fa-users\"></i> Confirmaciones de asistencia
                </a>
                <a href=\"#claves\" class=\"tab-link border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm\">
                    <i class=\"fas fa-comments\"></i> Claves
                </a>
                <a href=\"#intervenciones\" class=\"tab-link border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm\">
                    <i class=\"fas fa-user-injured\"></i> Intervenciones
                </a>
                <a href=\"#otros\" class=\"tab-link border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm\">
                    <i class=\"fas fa-file-signature\"></i> Otros
                </a>
                <a href=\"#materiales\" class=\"tab-link border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm\">
                    <i class=\"fas fa-boxes\"></i> Materiales
                </a>
                <a href=\"#vehiculos\" class=\"tab-link border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm\">
                    <i class=\"fas fa-truck\"></i> Vehículos
                </a>
                <a href=\"#incidencias\" class=\"tab-link border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm\">
                    <i class=\"fas fa-times\"></i> Incidencias
                </a>
                <a href=\"#comunicaciones\" class=\"tab-link border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm\">
                    <i class=\"fas fa-comments\"></i> Comunicaciones
                </a>
                <a href=\"#adjuntos\" class=\"tab-link border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm\">
                    <i class=\"fas fa-file-upload\"></i> Adjuntos
                </a>
            </nav>
        </div>

        <div class=\"mt-6\">
            {{ form_start(serviceForm, {'action': path('app_service_edit', {'id': service.id}), 'method': 'POST'}) }}
            <div id=\"datos_basicos\" class=\"tab-content active-content\">
                <div class=\"grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6\">
                    <div>
                        {{ form_label(serviceForm.numeration, 'Numeración') }}
                        {{ form_widget(serviceForm.numeration, {'attr': {'class': 'mt-1 block w-full rounded-md border-gray-300 shadow-sm'}}) }}
                    </div>
                    <div class=\"lg:col-span-2\">
                        {{ form_label(serviceForm.title, 'Título') }}
                        {{ form_widget(serviceForm.title, {'attr': {'class': 'mt-1 block w-full rounded-md border-gray-300 shadow-sm'}}) }}
                    </div>
                    <div></div>
                    <div>
                        {{ form_label(serviceForm.startDate, 'Fecha y hora de inicio') }}
                        {{ form_widget(serviceForm.startDate, {'attr': {'class': 'mt-1 block w-full rounded-md border-gray-300 shadow-sm'}}) }}
                    </div>
                    <div>
                        {{ form_label(serviceForm.endDate, 'Fecha y hora de finalización') }}
                        {{ form_widget(serviceForm.endDate, {'attr': {'class': 'mt-1 block w-full rounded-md border-gray-300 shadow-sm'}}) }}
                    </div>
                    <div>
                        {{ form_label(serviceForm.registrationLimitDate, 'Límite de inscripción') }}
                        {{ form_widget(serviceForm.registrationLimitDate, {'attr': {'class': 'mt-1 block w-full rounded-md border-gray-300 shadow-sm'}}) }}
                    </div>
                    <div>
                        {{ form_label(serviceForm.timeAtBase, 'Hora en base') }}
                        {{ form_widget(serviceForm.timeAtBase, {'attr': {'class': 'mt-1 block w-full rounded-md border-gray-300 shadow-sm'}}) }}
                    </div>
                    <div>
                        {{ form_label(serviceForm.departureTime, 'Hora de salida') }}
                        {{ form_widget(serviceForm.departureTime, {'attr': {'class': 'mt-1 block w-full rounded-md border-gray-300 shadow-sm'}}) }}
                    </div>
                    <div>
                        {{ form_label(serviceForm.maxAttendees, 'Máximo asistentes') }}
                        {{ form_widget(serviceForm.maxAttendees, {'attr': {'class': 'mt-1 block w-full rounded-md border-gray-300 shadow-sm'}}) }}
                    </div>
                    <div>
                        {{ form_label(serviceForm.type, 'Tipo de servicio') }}
                        {{ form_widget(serviceForm.type, {'attr': {'class': 'mt-1 block w-full rounded-md border-gray-300 shadow-sm'}}) }}
                    </div>
                    <div>
                        {{ form_label(serviceForm.category, 'Categoría') }}
                        {{ form_widget(serviceForm.category, {'attr': {'class': 'mt-1 block w-full rounded-md border-gray-300 shadow-sm'}}) }}
                    </div>
                </div>
                <div class=\"mt-6\">
                    {{ form_label(serviceForm.description, 'Descripción') }}
                    {{ form_widget(serviceForm.description, {'attr': {'class': 'mt-1 block w-full rounded-md border-gray-300 shadow-sm', 'rows': 10}}) }}
                </div>
            </div>

            <div id=\"avanzado\" class=\"tab-content hidden\">
                <div class=\"grid grid-cols-1 md:grid-cols-2 gap-6\">
                    <div>
                        {{ form_label(serviceForm.locality, 'Localidad') }}
                        {{ form_widget(serviceForm.locality, {'attr': {'class': 'mt-1 block w-full rounded-md border-gray-300 shadow-sm'}}) }}
                    </div>
                    <div>
                        {{ form_label(serviceForm.requester, 'Solicitante') }}
                        {{ form_widget(serviceForm.requester, {'attr': {'class': 'mt-1 block w-full rounded-md border-gray-300 shadow-sm'}}) }}
                    </div>
                    <div class=\"md:col-span-2\">
                        {{ form_widget(serviceForm.collaboration_with_other_services) }}
                    </div>
                </div>
            </div>

            <div id=\"asistencias\" class=\"tab-content hidden\">
                <p>Confirmaciones de asistencia (contenido pendiente)</p>
            </div>
            <div id=\"claves\" class=\"tab-content hidden\">
                <p>Claves (contenido pendiente)</p>
            </div>
            <div id=\"intervenciones\" class=\"tab-content hidden\">
                <p>Intervenciones (contenido pendiente)</p>
            </div>
            <div id=\"otros\" class=\"tab-content hidden\">
                <p>Otros (contenido pendiente)</p>
            </div>
            <div id=\"materiales\" class=\"tab-content hidden\">
                <p>Materiales (contenido pendiente)</p>
            </div>
            <div id=\"vehiculos\" class=\"tab-content hidden\">
                <p>Vehículos (contenido pendiente)</p>
            </div>
            <div id=\"incidencias\" class=\"tab-content hidden\">
                <p>Incidencias (contenido pendiente)</p>
            </div>
            <div id=\"comunicaciones\" class=\"tab-content hidden\">
                <p>Comunicaciones (contenido pendiente)</p>
            </div>
            <div id=\"adjuntos\" class=\"tab-content hidden\">
                <p>Adjuntos (contenido pendiente)</p>
            </div>

            <div class=\"flex justify-end gap-3 pt-6 border-t border-gray-200 mt-8\">
                <a href=\"{{ path('app_services_list') }}\" class=\"px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50\">
                    Cancelar
                </a>
                <button type=\"submit\" class=\"px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700\">
                    Guardar Cambios
                </button>
            </div>
            {{ form_end(serviceForm) }}
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tabs = document.querySelectorAll('.tab-link');
        const contents = document.querySelectorAll('.tab-content');

        tabs.forEach(tab => {
            tab.addEventListener('click', function (event) {
                event.preventDefault();

                tabs.forEach(t => {
                    t.classList.remove('active-tab', 'border-blue-500', 'text-blue-600');
                    t.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
                });

                this.classList.add('active-tab', 'border-blue-500', 'text-blue-600');
                this.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');

                contents.forEach(content => {
                    content.classList.add('hidden');
                });

                const activeContent = document.querySelector(this.getAttribute('href'));
                if (activeContent) {
                    activeContent.classList.remove('hidden');
                }
            });
        });
    });
</script>
{% endblock %}
", "service/view.html.twig", "C:\\xampp\\htdocs\\gesion_volunratios\\templates\\service\\view.html.twig");
    }
}
