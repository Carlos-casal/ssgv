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

/* service/new.html.twig */
class __TwigTemplate_3f30955e0c12e3514290f26934605d26 extends Template
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
            'title' => [$this, 'block_title'],
            'body' => [$this, 'block_body'],
        ];
    }

    protected function doGetParent(array $context): bool|string|Template|TemplateWrapper
    {
        // line 1
        return "base.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "service/new.html.twig"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "service/new.html.twig"));

        $this->parent = $this->load("base.html.twig", 1);
        yield from $this->parent->unwrap()->yield($context, array_merge($this->blocks, $blocks));
        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

    }

    // line 3
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_title(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "title"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "title"));

        yield "Crear Nuevo Servicio";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        yield from [];
    }

    // line 5
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_body(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "body"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "block", "body"));

        // line 6
        yield "    <div class=\"container mx-auto px-4 py-8\"> ";
        // line 7
        yield "        <h1 class=\"text-3xl font-bold mb-6 text-gray-800\">Crear Nuevo Servicio</h1>

        ";
        // line 10
        yield "        ";
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, (isset($context["app"]) || array_key_exists("app", $context) ? $context["app"] : (function () { throw new RuntimeError('Variable "app" does not exist.', 10, $this->source); })()), "flashes", ["success"], "method", false, false, false, 10));
        foreach ($context['_seq'] as $context["_key"] => $context["message"]) {
            // line 11
            yield "            <div class=\"bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4\" role=\"alert\">
                <strong class=\"font-bold\">¡Éxito!</strong>
                <span class=\"block sm:inline\">";
            // line 13
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($context["message"], "html", null, true);
            yield "</span>
                <span class=\"absolute top-0 bottom-0 right-0 px-4 py-3\">
                    <svg class=\"fill-current h-6 w-6 text-green-500\" role=\"button\" xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 20 20\" onclick=\"this.parentElement.parentElement.style.display='none';\"><title>Close</title><path d=\"M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z\"/></svg>
                </span>
            </div>
        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['message'], $context['_parent']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 19
        yield "        ";
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, (isset($context["app"]) || array_key_exists("app", $context) ? $context["app"] : (function () { throw new RuntimeError('Variable "app" does not exist.', 19, $this->source); })()), "flashes", ["error"], "method", false, false, false, 19));
        foreach ($context['_seq'] as $context["_key"] => $context["message"]) {
            // line 20
            yield "            <div class=\"bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4\" role=\"alert\">
                <strong class=\"font-bold\">¡Error!</strong>
                <span class=\"block sm:inline\">";
            // line 22
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($context["message"], "html", null, true);
            yield "</span>
                <span class=\"absolute top-0 bottom-0 right-0 px-4 py-3\">
                    <svg class=\"fill-current h-6 w-6 text-red-500\" role=\"button\" xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 20 20\" onclick=\"this.parentElement.parentElement.style.display='none';\"><title>Close</title><path d=\"M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z\"/></svg>
                </span>
            </div>
        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['message'], $context['_parent']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 28
        yield "
        ";
        // line 30
        yield "        <div class=\"bg-white shadow-md rounded-lg p-6 mb-8\">
            ";
        // line 31
        yield         $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->renderBlock((isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 31, $this->source); })()), 'form_start', ["attr" => ["class" => "grid grid-cols-1 md:grid-cols-2 gap-6"]]);
        yield " ";
        // line 32
        yield "
            ";
        // line 34
        yield "            <div>
                ";
        // line 35
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 35, $this->source); })()), "numeration", [], "any", false, false, false, 35), 'label', ["label_attr" => ["class" => "block text-gray-700 text-sm font-bold mb-2"], "label" => "Numeración:"]);
        yield "
                ";
        // line 36
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 36, $this->source); })()), "numeration", [], "any", false, false, false, 36), 'widget', ["attr" => ["class" => "shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"]]);
        yield "
                ";
        // line 37
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 37, $this->source); })()), "numeration", [], "any", false, false, false, 37), 'errors', ["attr" => ["class" => "text-red-500 text-xs italic mt-1"]]);
        yield "
            </div>

            ";
        // line 41
        yield "            <div>
                ";
        // line 42
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 42, $this->source); })()), "title", [], "any", false, false, false, 42), 'label', ["label_attr" => ["class" => "block text-gray-700 text-sm font-bold mb-2"], "label" => "Título:"]);
        yield "
                ";
        // line 43
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 43, $this->source); })()), "title", [], "any", false, false, false, 43), 'widget', ["attr" => ["class" => "shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"]]);
        yield "
                ";
        // line 44
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 44, $this->source); })()), "title", [], "any", false, false, false, 44), 'errors', ["attr" => ["class" => "text-red-500 text-xs italic mt-1"]]);
        yield "
            </div>

            ";
        // line 48
        yield "            <div>
                ";
        // line 49
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 49, $this->source); })()), "slug", [], "any", false, false, false, 49), 'label', ["label_attr" => ["class" => "block text-gray-700 text-sm font-bold mb-2"], "label" => "Slug:"]);
        yield "
                ";
        // line 50
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 50, $this->source); })()), "slug", [], "any", false, false, false, 50), 'widget', ["attr" => ["class" => "shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 bg-gray-100 leading-tight focus:outline-none focus:shadow-outline", "readonly" => "readonly"]]);
        yield "
                ";
        // line 51
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 51, $this->source); })()), "slug", [], "any", false, false, false, 51), 'errors', ["attr" => ["class" => "text-red-500 text-xs italic mt-1"]]);
        yield "
            </div>

            ";
        // line 55
        yield "            <div>
                ";
        // line 56
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 56, $this->source); })()), "startDate", [], "any", false, false, false, 56), 'label', ["label_attr" => ["class" => "block text-gray-700 text-sm font-bold mb-2"], "label" => "Fecha de Inicio:"]);
        yield "
                ";
        // line 57
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 57, $this->source); })()), "startDate", [], "any", false, false, false, 57), 'widget', ["attr" => ["class" => "shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"]]);
        yield "
                ";
        // line 58
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 58, $this->source); })()), "startDate", [], "any", false, false, false, 58), 'errors', ["attr" => ["class" => "text-red-500 text-xs italic mt-1"]]);
        yield "
            </div>

            ";
        // line 62
        yield "            <div>
                ";
        // line 63
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 63, $this->source); })()), "endDate", [], "any", false, false, false, 63), 'label', ["label_attr" => ["class" => "block text-gray-700 text-sm font-bold mb-2"], "label" => "Fecha de Fin:"]);
        yield "
                ";
        // line 64
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 64, $this->source); })()), "endDate", [], "any", false, false, false, 64), 'widget', ["attr" => ["class" => "shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"]]);
        yield "
                ";
        // line 65
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 65, $this->source); })()), "endDate", [], "any", false, false, false, 65), 'errors', ["attr" => ["class" => "text-red-500 text-xs italic mt-1"]]);
        yield "
            </div>

            ";
        // line 69
        yield "            <div>
                ";
        // line 70
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 70, $this->source); })()), "registrationLimitDate", [], "any", false, false, false, 70), 'label', ["label_attr" => ["class" => "block text-gray-700 text-sm font-bold mb-2"], "label" => "Fecha Límite de Registro:"]);
        yield "
                ";
        // line 71
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 71, $this->source); })()), "registrationLimitDate", [], "any", false, false, false, 71), 'widget', ["attr" => ["class" => "shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"]]);
        yield "
                ";
        // line 72
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 72, $this->source); })()), "registrationLimitDate", [], "any", false, false, false, 72), 'errors', ["attr" => ["class" => "text-red-500 text-xs italic mt-1"]]);
        yield "
            </div>

            ";
        // line 76
        yield "            <div>
                ";
        // line 77
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 77, $this->source); })()), "timeAtBase", [], "any", false, false, false, 77), 'label', ["label_attr" => ["class" => "block text-gray-700 text-sm font-bold mb-2"], "label" => "Hora en Base:"]);
        yield "
                ";
        // line 78
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 78, $this->source); })()), "timeAtBase", [], "any", false, false, false, 78), 'widget', ["attr" => ["class" => "shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"]]);
        yield "
                ";
        // line 79
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 79, $this->source); })()), "timeAtBase", [], "any", false, false, false, 79), 'errors', ["attr" => ["class" => "text-red-500 text-xs italic mt-1"]]);
        yield "
            </div>

            ";
        // line 83
        yield "            <div>
                ";
        // line 84
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 84, $this->source); })()), "departureTime", [], "any", false, false, false, 84), 'label', ["label_attr" => ["class" => "block text-gray-700 text-sm font-bold mb-2"], "label" => "Hora de Salida:"]);
        yield "
                ";
        // line 85
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 85, $this->source); })()), "departureTime", [], "any", false, false, false, 85), 'widget', ["attr" => ["class" => "shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"]]);
        yield "
                ";
        // line 86
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 86, $this->source); })()), "departureTime", [], "any", false, false, false, 86), 'errors', ["attr" => ["class" => "text-red-500 text-xs italic mt-1"]]);
        yield "
            </div>

            ";
        // line 90
        yield "            <div>
                ";
        // line 91
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 91, $this->source); })()), "maxAttendees", [], "any", false, false, false, 91), 'label', ["label_attr" => ["class" => "block text-gray-700 text-sm font-bold mb-2"], "label" => "Máximo de Asistentes:"]);
        yield "
                ";
        // line 92
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 92, $this->source); })()), "maxAttendees", [], "any", false, false, false, 92), 'widget', ["attr" => ["class" => "shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"]]);
        yield "
                ";
        // line 93
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 93, $this->source); })()), "maxAttendees", [], "any", false, false, false, 93), 'errors', ["attr" => ["class" => "text-red-500 text-xs italic mt-1"]]);
        yield "
            </div>

            ";
        // line 97
        yield "            <div>
                ";
        // line 98
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 98, $this->source); })()), "type", [], "any", false, false, false, 98), 'label', ["label_attr" => ["class" => "block text-gray-700 text-sm font-bold mb-2"], "label" => "Tipo:"]);
        yield "
                ";
        // line 99
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 99, $this->source); })()), "type", [], "any", false, false, false, 99), 'widget', ["attr" => ["class" => "shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline appearance-none"]]);
        yield " ";
        // line 100
        yield "                ";
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 100, $this->source); })()), "type", [], "any", false, false, false, 100), 'errors', ["attr" => ["class" => "text-red-500 text-xs italic mt-1"]]);
        yield "
            </div>

            ";
        // line 104
        yield "            <div>
                ";
        // line 105
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 105, $this->source); })()), "category", [], "any", false, false, false, 105), 'label', ["label_attr" => ["class" => "block text-gray-700 text-sm font-bold mb-2"], "label" => "Categoría:"]);
        yield "
                ";
        // line 106
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 106, $this->source); })()), "category", [], "any", false, false, false, 106), 'widget', ["attr" => ["class" => "shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline appearance-none"]]);
        yield " ";
        // line 107
        yield "                ";
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 107, $this->source); })()), "category", [], "any", false, false, false, 107), 'errors', ["attr" => ["class" => "text-red-500 text-xs italic mt-1"]]);
        yield "
            </div>

            ";
        // line 111
        yield "            <div>
                ";
        // line 112
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 112, $this->source); })()), "recipients", [], "any", false, false, false, 112), 'label', ["label_attr" => ["class" => "block text-gray-700 text-sm font-bold mb-2"], "label" => "Receptores:"]);
        yield "
                ";
        // line 113
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 113, $this->source); })()), "recipients", [], "any", false, false, false, 113), 'widget', ["attr" => ["class" => "shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"]]);
        yield " ";
        // line 114
        yield "                ";
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 114, $this->source); })()), "recipients", [], "any", false, false, false, 114), 'errors', ["attr" => ["class" => "text-red-500 text-xs italic mt-1"]]);
        yield "
            </div>
            
            ";
        // line 118
        yield "            <div>
                ";
        // line 119
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 119, $this->source); })()), "eys", [], "any", false, false, false, 119), 'label', ["label_attr" => ["class" => "block text-gray-700 text-sm font-bold mb-2"], "label" => "Eys:"]);
        yield "
                ";
        // line 120
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 120, $this->source); })()), "eys", [], "any", false, false, false, 120), 'widget', ["attr" => ["class" => "shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"]]);
        yield "
                ";
        // line 121
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 121, $this->source); })()), "eys", [], "any", false, false, false, 121), 'errors', ["attr" => ["class" => "text-red-500 text-xs italic mt-1"]]);
        yield "
            </div>

            ";
        // line 125
        yield "            <div class=\"md:col-span-2\"> ";
        // line 126
        yield "                ";
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 126, $this->source); })()), "description", [], "any", false, false, false, 126), 'label', ["label_attr" => ["class" => "block text-gray-700 text-sm font-bold mb-2"], "label" => "Descripción:"]);
        yield "
                ";
        // line 127
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 127, $this->source); })()), "description", [], "any", false, false, false, 127), 'widget', ["attr" => ["class" => "shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline h-32 resize-y"]]);
        yield " ";
        // line 128
        yield "                ";
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 128, $this->source); })()), "description", [], "any", false, false, false, 128), 'errors', ["attr" => ["class" => "text-red-500 text-xs italic mt-1"]]);
        yield "
            </div>
            
            ";
        // line 132
        yield "            ";
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock((isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 132, $this->source); })()), 'rest');
        yield "

            ";
        // line 135
        yield "            <div class=\"md:col-span-2 flex items-center justify-end mt-6\"> ";
        // line 136
        yield "                <button type=\"submit\" class=\"bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline\">
                    Guardar Servicio
                </button>
                <a href=\"";
        // line 139
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("list_service");
        yield "\" class=\"bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline ml-3\">
                    Volver a la Lista
                </a>
            </div>

            ";
        // line 144
        yield         $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->renderBlock((isset($context["serviceForm"]) || array_key_exists("serviceForm", $context) ? $context["serviceForm"] : (function () { throw new RuntimeError('Variable "serviceForm" does not exist.', 144, $this->source); })()), 'form_end');
        yield "
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
        return "service/new.html.twig";
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
        return array (  419 => 144,  411 => 139,  406 => 136,  404 => 135,  398 => 132,  391 => 128,  388 => 127,  383 => 126,  381 => 125,  375 => 121,  371 => 120,  367 => 119,  364 => 118,  357 => 114,  354 => 113,  350 => 112,  347 => 111,  340 => 107,  337 => 106,  333 => 105,  330 => 104,  323 => 100,  320 => 99,  316 => 98,  313 => 97,  307 => 93,  303 => 92,  299 => 91,  296 => 90,  290 => 86,  286 => 85,  282 => 84,  279 => 83,  273 => 79,  269 => 78,  265 => 77,  262 => 76,  256 => 72,  252 => 71,  248 => 70,  245 => 69,  239 => 65,  235 => 64,  231 => 63,  228 => 62,  222 => 58,  218 => 57,  214 => 56,  211 => 55,  205 => 51,  201 => 50,  197 => 49,  194 => 48,  188 => 44,  184 => 43,  180 => 42,  177 => 41,  171 => 37,  167 => 36,  163 => 35,  160 => 34,  157 => 32,  154 => 31,  151 => 30,  148 => 28,  136 => 22,  132 => 20,  127 => 19,  115 => 13,  111 => 11,  106 => 10,  102 => 7,  100 => 6,  87 => 5,  64 => 3,  41 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("{% extends 'base.html.twig' %} {# Asegúrate de que tu base.html.twig incluye Tailwind CSS compilado #}

{% block title %}Crear Nuevo Servicio{% endblock %}

{% block body %}
    <div class=\"container mx-auto px-4 py-8\"> {# Contenedor centrado y con padding #}
        <h1 class=\"text-3xl font-bold mb-6 text-gray-800\">Crear Nuevo Servicio</h1>

        {# Mensajes flash (ej. \"¡El servicio ha sido creado con éxito!\") #}
        {% for message in app.flashes('success') %}
            <div class=\"bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4\" role=\"alert\">
                <strong class=\"font-bold\">¡Éxito!</strong>
                <span class=\"block sm:inline\">{{ message }}</span>
                <span class=\"absolute top-0 bottom-0 right-0 px-4 py-3\">
                    <svg class=\"fill-current h-6 w-6 text-green-500\" role=\"button\" xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 20 20\" onclick=\"this.parentElement.parentElement.style.display='none';\"><title>Close</title><path d=\"M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z\"/></svg>
                </span>
            </div>
        {% endfor %}
        {% for message in app.flashes('error') %}
            <div class=\"bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4\" role=\"alert\">
                <strong class=\"font-bold\">¡Error!</strong>
                <span class=\"block sm:inline\">{{ message }}</span>
                <span class=\"absolute top-0 bottom-0 right-0 px-4 py-3\">
                    <svg class=\"fill-current h-6 w-6 text-red-500\" role=\"button\" xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 20 20\" onclick=\"this.parentElement.parentElement.style.display='none';\"><title>Close</title><path d=\"M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z\"/></svg>
                </span>
            </div>
        {% endfor %}

        {# Contenedor principal del formulario con sombra y bordes redondeados #}
        <div class=\"bg-white shadow-md rounded-lg p-6 mb-8\">
            {{ form_start(serviceForm, {'attr': {'class': 'grid grid-cols-1 md:grid-cols-2 gap-6'}}) }} {# Usamos grid para las columnas #}

            {# Campo Numeración #}
            <div>
                {{ form_label(serviceForm.numeration, 'Numeración:', {'label_attr': {'class': 'block text-gray-700 text-sm font-bold mb-2'}}) }}
                {{ form_widget(serviceForm.numeration, {'attr': {'class': 'shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline'}}) }}
                {{ form_errors(serviceForm.numeration, {'attr': {'class': 'text-red-500 text-xs italic mt-1'}}) }}
            </div>

            {# Campo Título #}
            <div>
                {{ form_label(serviceForm.title, 'Título:', {'label_attr': {'class': 'block text-gray-700 text-sm font-bold mb-2'}}) }}
                {{ form_widget(serviceForm.title, {'attr': {'class': 'shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline'}}) }}
                {{ form_errors(serviceForm.title, {'attr': {'class': 'text-red-500 text-xs italic mt-1'}}) }}
            </div>

            {# Campo Slug (de solo lectura si es auto-generado) #}
            <div>
                {{ form_label(serviceForm.slug, 'Slug:', {'label_attr': {'class': 'block text-gray-700 text-sm font-bold mb-2'}}) }}
                {{ form_widget(serviceForm.slug, {'attr': {'class': 'shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 bg-gray-100 leading-tight focus:outline-none focus:shadow-outline', 'readonly': 'readonly'}}) }}
                {{ form_errors(serviceForm.slug, {'attr': {'class': 'text-red-500 text-xs italic mt-1'}}) }}
            </div>

            {# Campo Fecha de Inicio #}
            <div>
                {{ form_label(serviceForm.startDate, 'Fecha de Inicio:', {'label_attr': {'class': 'block text-gray-700 text-sm font-bold mb-2'}}) }}
                {{ form_widget(serviceForm.startDate, {'attr': {'class': 'shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline'}}) }}
                {{ form_errors(serviceForm.startDate, {'attr': {'class': 'text-red-500 text-xs italic mt-1'}}) }}
            </div>

            {# Campo Fecha de Fin #}
            <div>
                {{ form_label(serviceForm.endDate, 'Fecha de Fin:', {'label_attr': {'class': 'block text-gray-700 text-sm font-bold mb-2'}}) }}
                {{ form_widget(serviceForm.endDate, {'attr': {'class': 'shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline'}}) }}
                {{ form_errors(serviceForm.endDate, {'attr': {'class': 'text-red-500 text-xs italic mt-1'}}) }}
            </div>

            {# Campo Fecha Límite de Registro #}
            <div>
                {{ form_label(serviceForm.registrationLimitDate, 'Fecha Límite de Registro:', {'label_attr': {'class': 'block text-gray-700 text-sm font-bold mb-2'}}) }}
                {{ form_widget(serviceForm.registrationLimitDate, {'attr': {'class': 'shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline'}}) }}
                {{ form_errors(serviceForm.registrationLimitDate, {'attr': {'class': 'text-red-500 text-xs italic mt-1'}}) }}
            </div>

            {# Campo Hora en Base #}
            <div>
                {{ form_label(serviceForm.timeAtBase, 'Hora en Base:', {'label_attr': {'class': 'block text-gray-700 text-sm font-bold mb-2'}}) }}
                {{ form_widget(serviceForm.timeAtBase, {'attr': {'class': 'shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline'}}) }}
                {{ form_errors(serviceForm.timeAtBase, {'attr': {'class': 'text-red-500 text-xs italic mt-1'}}) }}
            </div>

            {# Campo Hora de Salida #}
            <div>
                {{ form_label(serviceForm.departureTime, 'Hora de Salida:', {'label_attr': {'class': 'block text-gray-700 text-sm font-bold mb-2'}}) }}
                {{ form_widget(serviceForm.departureTime, {'attr': {'class': 'shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline'}}) }}
                {{ form_errors(serviceForm.departureTime, {'attr': {'class': 'text-red-500 text-xs italic mt-1'}}) }}
            </div>

            {# Campo Máximo de Asistentes #}
            <div>
                {{ form_label(serviceForm.maxAttendees, 'Máximo de Asistentes:', {'label_attr': {'class': 'block text-gray-700 text-sm font-bold mb-2'}}) }}
                {{ form_widget(serviceForm.maxAttendees, {'attr': {'class': 'shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline'}}) }}
                {{ form_errors(serviceForm.maxAttendees, {'attr': {'class': 'text-red-500 text-xs italic mt-1'}}) }}
            </div>

            {# Campo Tipo (si es un select) #}
            <div>
                {{ form_label(serviceForm.type, 'Tipo:', {'label_attr': {'class': 'block text-gray-700 text-sm font-bold mb-2'}}) }}
                {{ form_widget(serviceForm.type, {'attr': {'class': 'shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline appearance-none'}}) }} {# Para select inputs #}
                {{ form_errors(serviceForm.type, {'attr': {'class': 'text-red-500 text-xs italic mt-1'}}) }}
            </div>

            {# Campo Categoría (si es un select) #}
            <div>
                {{ form_label(serviceForm.category, 'Categoría:', {'label_attr': {'class': 'block text-gray-700 text-sm font-bold mb-2'}}) }}
                {{ form_widget(serviceForm.category, {'attr': {'class': 'shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline appearance-none'}}) }} {# Para select inputs #}
                {{ form_errors(serviceForm.category, {'attr': {'class': 'text-red-500 text-xs italic mt-1'}}) }}
            </div>

            {# Campo Recipients (si es un select múltiple o colección) #}
            <div>
                {{ form_label(serviceForm.recipients, 'Receptores:', {'label_attr': {'class': 'block text-gray-700 text-sm font-bold mb-2'}}) }}
                {{ form_widget(serviceForm.recipients, {'attr': {'class': 'shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline'}}) }} {# Puede que necesites una librería JS para un select múltiple con buen estilo #}
                {{ form_errors(serviceForm.recipients, {'attr': {'class': 'text-red-500 text-xs italic mt-1'}}) }}
            </div>
            
            {# Campo Eys #}
            <div>
                {{ form_label(serviceForm.eys, 'Eys:', {'label_attr': {'class': 'block text-gray-700 text-sm font-bold mb-2'}}) }}
                {{ form_widget(serviceForm.eys, {'attr': {'class': 'shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline'}}) }}
                {{ form_errors(serviceForm.eys, {'attr': {'class': 'text-red-500 text-xs italic mt-1'}}) }}
            </div>

            {# Campo Descripción (ocupa todo el ancho, por eso no está en el grid) #}
            <div class=\"md:col-span-2\"> {# Ocupa 2 columnas en pantallas medianas y más grandes #}
                {{ form_label(serviceForm.description, 'Descripción:', {'label_attr': {'class': 'block text-gray-700 text-sm font-bold mb-2'}}) }}
                {{ form_widget(serviceForm.description, {'attr': {'class': 'shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline h-32 resize-y'}}) }} {# h-32 para altura, resize-y para redimensionar verticalmente #}
                {{ form_errors(serviceForm.description, {'attr': {'class': 'text-red-500 text-xs italic mt-1'}}) }}
            </div>
            
            {# Renderiza los campos restantes si los hubiera, o el campo \"_token\" #}
            {{ form_rest(serviceForm) }}

            {# Botones de acción #}
            <div class=\"md:col-span-2 flex items-center justify-end mt-6\"> {# Ocupa 2 columnas, alinea a la derecha #}
                <button type=\"submit\" class=\"bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline\">
                    Guardar Servicio
                </button>
                <a href=\"{{ path('list_service') }}\" class=\"bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline ml-3\">
                    Volver a la Lista
                </a>
            </div>

            {{ form_end(serviceForm) }}
        </div>
    </div>
{% endblock %}", "service/new.html.twig", "C:\\xampp\\htdocs\\gesion_volunratios\\templates\\service\\new.html.twig");
    }
}
