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

/* service/show.html.twig */
class __TwigTemplate_e8a24189daa31547035d205bee15d748 extends Template
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
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "service/show.html.twig"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "service/show.html.twig"));

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

        yield "Editar Servicio: ";
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
    <div class=\"max-w-3xl mx-auto\">
        <div class=\"bg-white rounded-xl shadow-sm border border-gray-200 p-6\">
            <div class=\"mb-6\">
                <h2 class=\"text-xl font-semibold text-gray-900\">Editar Servicio</h2>
                <p class=\"text-gray-600 mt-1\">Modifica la información del servicio \"";
        // line 11
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, (isset($context["service"]) || array_key_exists("service", $context) ? $context["service"] : (function () { throw new RuntimeError('Variable "service" does not exist.', 11, $this->source); })()), "title", [], "any", false, false, false, 11), "html", null, true);
        yield "\".</p>
            </div>

            ";
        // line 15
        yield "            ";
        // line 16
        yield "            ";
        yield         $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->renderBlock((isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 16, $this->source); })()), 'form_start', ["action" => $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_service_update", ["id" => CoreExtension::getAttribute($this->env, $this->source, (isset($context["service"]) || array_key_exists("service", $context) ? $context["service"] : (function () { throw new RuntimeError('Variable "service" does not exist.', 16, $this->source); })()), "id", [], "any", false, false, false, 16)]), "method" => "POST", "attr" => ["class" => "space-y-6"]]);
        yield "

                <div class=\"grid grid-cols-1 md:grid-cols-2 gap-6\">
                    <div>
                        ";
        // line 20
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 20, $this->source); })()), "numeration", [], "any", false, false, false, 20), 'label', ["label_attr" => ["class" => "block text-sm font-medium text-gray-700 mb-1"], "label" => "Numeración:"]);
        // line 21
        yield "
                        ";
        // line 22
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 22, $this->source); })()), "numeration", [], "any", false, false, false, 22), 'widget', ["attr" => ["class" => "mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"]]);
        // line 23
        yield "
                        ";
        // line 24
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 24, $this->source); })()), "numeration", [], "any", false, false, false, 24), 'errors');
        yield "
                    </div>

                    <div>
                        ";
        // line 28
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 28, $this->source); })()), "title", [], "any", false, false, false, 28), 'label', ["label_attr" => ["class" => "block text-sm font-medium text-gray-700 mb-1"], "label" => "Título:"]);
        // line 29
        yield "
                        ";
        // line 30
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 30, $this->source); })()), "title", [], "any", false, false, false, 30), 'widget', ["attr" => ["class" => "mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"]]);
        // line 31
        yield "
                        ";
        // line 32
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 32, $this->source); })()), "title", [], "any", false, false, false, 32), 'errors');
        yield "
                    </div>

                    <div>
                        ";
        // line 36
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 36, $this->source); })()), "slug", [], "any", false, false, false, 36), 'label', ["label_attr" => ["class" => "block text-sm font-medium text-gray-700 mb-1"], "label" => "Slug:"]);
        // line 37
        yield "
                        ";
        // line 38
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 38, $this->source); })()), "slug", [], "any", false, false, false, 38), 'widget', ["attr" => ["class" => "mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm bg-gray-50", "readonly" => CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 38, $this->source); })()), "slug", [], "any", false, false, false, 38), "vars", [], "any", false, false, false, 38), "disabled", [], "any", false, false, false, 38)]]);
        // line 39
        yield " 
                        ";
        // line 40
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 40, $this->source); })()), "slug", [], "any", false, false, false, 40), 'errors');
        yield "
                        <p class=\"mt-1 text-xs text-gray-500\">El slug se genera automáticamente si se deja vacío o puede ser modificado.</p>
                    </div>

                    <div>
                        ";
        // line 45
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 45, $this->source); })()), "startDate", [], "any", false, false, false, 45), 'label', ["label_attr" => ["class" => "block text-sm font-medium text-gray-700 mb-1"], "label" => "Fecha de Inicio:"]);
        // line 46
        yield "
                        ";
        // line 47
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 47, $this->source); })()), "startDate", [], "any", false, false, false, 47), 'widget', ["attr" => ["class" => "mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"]]);
        // line 48
        yield "
                        ";
        // line 49
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 49, $this->source); })()), "startDate", [], "any", false, false, false, 49), 'errors');
        yield "
                    </div>

                    <div>
                        ";
        // line 53
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 53, $this->source); })()), "endDate", [], "any", false, false, false, 53), 'label', ["label_attr" => ["class" => "block text-sm font-medium text-gray-700 mb-1"], "label" => "Fecha de Fin:"]);
        // line 54
        yield "
                        ";
        // line 55
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 55, $this->source); })()), "endDate", [], "any", false, false, false, 55), 'widget', ["attr" => ["class" => "mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"]]);
        // line 56
        yield "
                        ";
        // line 57
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 57, $this->source); })()), "endDate", [], "any", false, false, false, 57), 'errors');
        yield "
                    </div>

                    <div>
                        ";
        // line 61
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 61, $this->source); })()), "registrationLimitDate", [], "any", false, false, false, 61), 'label', ["label_attr" => ["class" => "block text-sm font-medium text-gray-700 mb-1"], "label" => "Fecha Límite de Registro:"]);
        // line 62
        yield "
                        ";
        // line 63
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 63, $this->source); })()), "registrationLimitDate", [], "any", false, false, false, 63), 'widget', ["attr" => ["class" => "mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"]]);
        // line 64
        yield "
                        ";
        // line 65
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 65, $this->source); })()), "registrationLimitDate", [], "any", false, false, false, 65), 'errors');
        yield "
                    </div>

                    <div>
                        ";
        // line 69
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 69, $this->source); })()), "timeAtBase", [], "any", false, false, false, 69), 'label', ["label_attr" => ["class" => "block text-sm font-medium text-gray-700 mb-1"], "label" => "Hora en Base:"]);
        // line 70
        yield "
                        ";
        // line 71
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 71, $this->source); })()), "timeAtBase", [], "any", false, false, false, 71), 'widget', ["attr" => ["class" => "mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"]]);
        // line 72
        yield "
                        ";
        // line 73
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 73, $this->source); })()), "timeAtBase", [], "any", false, false, false, 73), 'errors');
        yield "
                    </div>

                    <div>
                        ";
        // line 77
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 77, $this->source); })()), "departureTime", [], "any", false, false, false, 77), 'label', ["label_attr" => ["class" => "block text-sm font-medium text-gray-700 mb-1"], "label" => "Hora de Salida:"]);
        // line 78
        yield "
                        ";
        // line 79
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 79, $this->source); })()), "departureTime", [], "any", false, false, false, 79), 'widget', ["attr" => ["class" => "mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"]]);
        // line 80
        yield "
                        ";
        // line 81
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 81, $this->source); })()), "departureTime", [], "any", false, false, false, 81), 'errors');
        yield "
                    </div>

                    <div>
                        ";
        // line 85
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 85, $this->source); })()), "maxAttendees", [], "any", false, false, false, 85), 'label', ["label_attr" => ["class" => "block text-sm font-medium text-gray-700 mb-1"], "label" => "Máximo de Asistentes:"]);
        // line 86
        yield "
                        ";
        // line 87
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 87, $this->source); })()), "maxAttendees", [], "any", false, false, false, 87), 'widget', ["attr" => ["class" => "mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"]]);
        // line 88
        yield "
                        ";
        // line 89
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 89, $this->source); })()), "maxAttendees", [], "any", false, false, false, 89), 'errors');
        yield "
                    </div>

                    <div>
                        ";
        // line 93
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 93, $this->source); })()), "type", [], "any", false, false, false, 93), 'label', ["label_attr" => ["class" => "block text-sm font-medium text-gray-700 mb-1"], "label" => "Tipo:"]);
        // line 94
        yield "
                        ";
        // line 95
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 95, $this->source); })()), "type", [], "any", false, false, false, 95), 'widget', ["attr" => ["class" => "mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"]]);
        // line 96
        yield "
                        ";
        // line 97
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 97, $this->source); })()), "type", [], "any", false, false, false, 97), 'errors');
        yield "
                    </div>

                    <div>
                        ";
        // line 101
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 101, $this->source); })()), "category", [], "any", false, false, false, 101), 'label', ["label_attr" => ["class" => "block text-sm font-medium text-gray-700 mb-1"], "label" => "Categoría:"]);
        // line 102
        yield "
                        ";
        // line 103
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 103, $this->source); })()), "category", [], "any", false, false, false, 103), 'widget', ["attr" => ["class" => "mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"]]);
        // line 104
        yield "
                        ";
        // line 105
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 105, $this->source); })()), "category", [], "any", false, false, false, 105), 'errors');
        yield "
                    </div>

                    <div>
                        ";
        // line 109
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 109, $this->source); })()), "recipients", [], "any", false, false, false, 109), 'label', ["label_attr" => ["class" => "block text-sm font-medium text-gray-700 mb-1"], "label" => "Receptores:"]);
        // line 110
        yield "
                        ";
        // line 111
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 111, $this->source); })()), "recipients", [], "any", false, false, false, 111), 'widget', ["attr" => ["class" => "mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"]]);
        // line 112
        yield " 
                        ";
        // line 113
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 113, $this->source); })()), "recipients", [], "any", false, false, false, 113), 'errors');
        yield "
                    </div>

                    <div>
                        ";
        // line 117
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 117, $this->source); })()), "eys", [], "any", false, false, false, 117), 'label', ["label_attr" => ["class" => "block text-sm font-medium text-gray-700 mb-1"], "label" => "EYS:"]);
        // line 118
        yield "
                        ";
        // line 119
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 119, $this->source); })()), "eys", [], "any", false, false, false, 119), 'widget', ["attr" => ["class" => "mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"]]);
        // line 120
        yield "
                        ";
        // line 121
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 121, $this->source); })()), "eys", [], "any", false, false, false, 121), 'errors');
        yield "
                    </div>
                </div>

                <div class=\"mt-6\">
                    ";
        // line 126
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 126, $this->source); })()), "description", [], "any", false, false, false, 126), 'label', ["label_attr" => ["class" => "block text-sm font-medium text-gray-700 mb-1"], "label" => "Descripción:"]);
        // line 127
        yield "
                    ";
        // line 128
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 128, $this->source); })()), "description", [], "any", false, false, false, 128), 'widget', ["attr" => ["class" => "mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm", "rows" => 5]]);
        // line 129
        yield "
                    ";
        // line 130
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 130, $this->source); })()), "description", [], "any", false, false, false, 130), 'errors');
        yield "
                </div>
                
                ";
        // line 134
        yield "                ";
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 134, $this->source); })()), "_token", [], "any", false, false, false, 134), 'row');
        yield "

                <div class=\"flex gap-3 pt-6 border-t border-gray-200 mt-8\">
                    ";
        // line 138
        yield "                    <a href=\"";
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_service_list");
        yield "\" 
                       class=\"flex-1 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors text-center text-sm font-medium\">
                        Cancelar
                    </a>
                    <button type=\"submit\" 
                            class=\"flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium\">
                        Guardar Cambios
                    </button>
                </div>
            ";
        // line 147
        yield         $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->renderBlock((isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 147, $this->source); })()), 'form_end');
        yield "
        </div>
    </div>
</div>
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
        return "service/show.html.twig";
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
        return array (  383 => 147,  370 => 138,  363 => 134,  357 => 130,  354 => 129,  352 => 128,  349 => 127,  347 => 126,  339 => 121,  336 => 120,  334 => 119,  331 => 118,  329 => 117,  322 => 113,  319 => 112,  317 => 111,  314 => 110,  312 => 109,  305 => 105,  302 => 104,  300 => 103,  297 => 102,  295 => 101,  288 => 97,  285 => 96,  283 => 95,  280 => 94,  278 => 93,  271 => 89,  268 => 88,  266 => 87,  263 => 86,  261 => 85,  254 => 81,  251 => 80,  249 => 79,  246 => 78,  244 => 77,  237 => 73,  234 => 72,  232 => 71,  229 => 70,  227 => 69,  220 => 65,  217 => 64,  215 => 63,  212 => 62,  210 => 61,  203 => 57,  200 => 56,  198 => 55,  195 => 54,  193 => 53,  186 => 49,  183 => 48,  181 => 47,  178 => 46,  176 => 45,  168 => 40,  165 => 39,  163 => 38,  160 => 37,  158 => 36,  151 => 32,  148 => 31,  146 => 30,  143 => 29,  141 => 28,  134 => 24,  131 => 23,  129 => 22,  126 => 21,  124 => 20,  116 => 16,  114 => 15,  108 => 11,  101 => 6,  88 => 5,  64 => 3,  41 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("{% extends 'layout/app.html.twig' %}

{% block page_title %}Editar Servicio: {{ service.title }}{% endblock %}

{% block content %}
<div class=\"p-6\">
    <div class=\"max-w-3xl mx-auto\">
        <div class=\"bg-white rounded-xl shadow-sm border border-gray-200 p-6\">
            <div class=\"mb-6\">
                <h2 class=\"text-xl font-semibold text-gray-900\">Editar Servicio</h2>
                <p class=\"text-gray-600 mt-1\">Modifica la información del servicio \"{{ service.title }}\".</p>
            </div>

            {# Ajusta 'app_service_update' al nombre de tu ruta para procesar la actualización del servicio #}
            {# Asume que serviceForm es el nombre de tu variable de formulario #}
            {{ form_start(serviceForm, {'action': path('app_service_update', {'id': service.id}), 'method': 'POST', 'attr': {'class': 'space-y-6'}}) }}

                <div class=\"grid grid-cols-1 md:grid-cols-2 gap-6\">
                    <div>
                        {{ form_label(serviceForm.numeration, 'Numeración:', {'label_attr': {'class': 'block text-sm font-medium text-gray-700 mb-1'}})
                        }}
                        {{ form_widget(serviceForm.numeration, {'attr': {'class': 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm'}})
                        }}
                        {{ form_errors(serviceForm.numeration) }}
                    </div>

                    <div>
                        {{ form_label(serviceForm.title, 'Título:', {'label_attr': {'class': 'block text-sm font-medium text-gray-700 mb-1'}})
                        }}
                        {{ form_widget(serviceForm.title, {'attr': {'class': 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm'}})
                        }}
                        {{ form_errors(serviceForm.title) }}
                    </div>

                    <div>
                        {{ form_label(serviceForm.slug, 'Slug:', {'label_attr': {'class': 'block text-sm font-medium text-gray-700 mb-1'}})
                        }}
                        {{ form_widget(serviceForm.slug, {'attr': {'class': 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm bg-gray-50', 'readonly': serviceForm.slug.vars.disabled }})
                        }} 
                        {{ form_errors(serviceForm.slug) }}
                        <p class=\"mt-1 text-xs text-gray-500\">El slug se genera automáticamente si se deja vacío o puede ser modificado.</p>
                    </div>

                    <div>
                        {{ form_label(serviceForm.startDate, 'Fecha de Inicio:', {'label_attr': {'class': 'block text-sm font-medium text-gray-700 mb-1'}})
                        }}
                        {{ form_widget(serviceForm.startDate, {'attr': {'class': 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm'}})
                        }}
                        {{ form_errors(serviceForm.startDate) }}
                    </div>

                    <div>
                        {{ form_label(serviceForm.endDate, 'Fecha de Fin:', {'label_attr': {'class': 'block text-sm font-medium text-gray-700 mb-1'}})
                        }}
                        {{ form_widget(serviceForm.endDate, {'attr': {'class': 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm'}})
                        }}
                        {{ form_errors(serviceForm.endDate) }}
                    </div>

                    <div>
                        {{ form_label(serviceForm.registrationLimitDate, 'Fecha Límite de Registro:', {'label_attr': {'class': 'block text-sm font-medium text-gray-700 mb-1'}})
                        }}
                        {{ form_widget(serviceForm.registrationLimitDate, {'attr': {'class': 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm'}})
                        }}
                        {{ form_errors(serviceForm.registrationLimitDate) }}
                    </div>

                    <div>
                        {{ form_label(serviceForm.timeAtBase, 'Hora en Base:', {'label_attr': {'class': 'block text-sm font-medium text-gray-700 mb-1'}})
                        }}
                        {{ form_widget(serviceForm.timeAtBase, {'attr': {'class': 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm'}})
                        }}
                        {{ form_errors(serviceForm.timeAtBase) }}
                    </div>

                    <div>
                        {{ form_label(serviceForm.departureTime, 'Hora de Salida:', {'label_attr': {'class': 'block text-sm font-medium text-gray-700 mb-1'}})
                        }}
                        {{ form_widget(serviceForm.departureTime, {'attr': {'class': 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm'}})
                        }}
                        {{ form_errors(serviceForm.departureTime) }}
                    </div>

                    <div>
                        {{ form_label(serviceForm.maxAttendees, 'Máximo de Asistentes:', {'label_attr': {'class': 'block text-sm font-medium text-gray-700 mb-1'}})
                        }}
                        {{ form_widget(serviceForm.maxAttendees, {'attr': {'class': 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm'}})
                        }}
                        {{ form_errors(serviceForm.maxAttendees) }}
                    </div>

                    <div>
                        {{ form_label(serviceForm.type, 'Tipo:', {'label_attr': {'class': 'block text-sm font-medium text-gray-700 mb-1'}})
                        }}
                        {{ form_widget(serviceForm.type, {'attr': {'class': 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm'}})
                        }}
                        {{ form_errors(serviceForm.type) }}
                    </div>

                    <div>
                        {{ form_label(serviceForm.category, 'Categoría:', {'label_attr': {'class': 'block text-sm font-medium text-gray-700 mb-1'}})
                        }}
                        {{ form_widget(serviceForm.category, {'attr': {'class': 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm'}})
                        }}
                        {{ form_errors(serviceForm.category) }}
                    </div>

                    <div>
                        {{ form_label(serviceForm.recipients, 'Receptores:', {'label_attr': {'class': 'block text-sm font-medium text-gray-700 mb-1'}})
                        }}
                        {{ form_widget(serviceForm.recipients, {'attr': {'class': 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm'}})
                        }} 
                        {{ form_errors(serviceForm.recipients) }}
                    </div>

                    <div>
                        {{ form_label(serviceForm.eys, 'EYS:', {'label_attr': {'class': 'block text-sm font-medium text-gray-700 mb-1'}})
                        }}
                        {{ form_widget(serviceForm.eys, {'attr': {'class': 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm'}})
                        }}
                        {{ form_errors(serviceForm.eys) }}
                    </div>
                </div>

                <div class=\"mt-6\">
                    {{ form_label(serviceForm.description, 'Descripción:', {'label_attr': {'class': 'block text-sm font-medium text-gray-700 mb-1'}})
                    }}
                    {{ form_widget(serviceForm.description, {'attr': {'class': 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm', 'rows': 5}})
                    }}
                    {{ form_errors(serviceForm.description) }}
                </div>
                
                {# Campo para el token CSRF, usualmente renderizado por form_end o form_rest si no se personaliza #}
                {{ form_row(serviceForm._token) }}

                <div class=\"flex gap-3 pt-6 border-t border-gray-200 mt-8\">
                    {# Ajusta 'app_service_list' al nombre de tu ruta de listado o a la ruta de visualización del servicio #}
                    <a href=\"{{ path('app_service_list') }}\" 
                       class=\"flex-1 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors text-center text-sm font-medium\">
                        Cancelar
                    </a>
                    <button type=\"submit\" 
                            class=\"flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium\">
                        Guardar Cambios
                    </button>
                </div>
            {{ form_end(serviceForm) }}
        </div>
    </div>
</div>
{% endblock %}", "service/show.html.twig", "C:\\xampp\\htdocs\\gesion_volunratios\\templates\\service\\show.html.twig");
    }
}
