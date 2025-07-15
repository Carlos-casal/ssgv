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

/* common/coming_soon.html.twig */
class __TwigTemplate_7d4f4be0311aafcf732d82849773785c extends Template
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
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "common/coming_soon.html.twig"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "common/coming_soon.html.twig"));

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

        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((isset($context["title"]) || array_key_exists("title", $context) ? $context["title"] : (function () { throw new RuntimeError('Variable "title" does not exist.', 3, $this->source); })()), "html", null, true);
        
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
    <div class=\"max-w-2xl mx-auto text-center\">
        <div class=\"inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-6\">
            <i data-lucide=\"construction\" class=\"w-8 h-8 text-blue-600\"></i>
        </div>
        
        <h2 class=\"text-2xl font-bold text-gray-900 mb-4\">";
        // line 12
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((isset($context["title"]) || array_key_exists("title", $context) ? $context["title"] : (function () { throw new RuntimeError('Variable "title" does not exist.', 12, $this->source); })()), "html", null, true);
        yield "</h2>
        
        <p class=\"text-gray-600 mb-8\">
            ";
        // line 15
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((array_key_exists("description", $context)) ? (Twig\Extension\CoreExtension::default((isset($context["description"]) || array_key_exists("description", $context) ? $context["description"] : (function () { throw new RuntimeError('Variable "description" does not exist.', 15, $this->source); })()), "Esta sección está en desarrollo. Próximamente estará disponible con todas las funcionalidades necesarias.")) : ("Esta sección está en desarrollo. Próximamente estará disponible con todas las funcionalidades necesarias.")), "html", null, true);
        yield "
        </p>

        <div class=\"bg-blue-50 border border-blue-200 rounded-lg p-6\">
            <h3 class=\"font-semibold text-blue-900 mb-2\">¿Qué encontrarás aquí?</h3>
            <ul class=\"text-sm text-blue-800 space-y-1\">
                <li>• Gestión completa de datos</li>
                <li>• Informes y estadísticas detalladas</li>
                <li>• Exportación de información</li>
                <li>• Interface intuitiva y moderna</li>
            </ul>
        </div>

        <div class=\"mt-8 p-4 bg-gray-50 rounded-lg\">
            <p class=\"text-sm text-gray-600\">
                <strong>Nota:</strong> El sistema está siendo desarrollado por módulos. 
                Cada sección será implementada con todas las funcionalidades requeridas.
            </p>
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
        return "common/coming_soon.html.twig";
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
        return array (  114 => 15,  108 => 12,  100 => 6,  87 => 5,  64 => 3,  41 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("{% extends 'layout/app.html.twig' %}

{% block page_title %}{{ title }}{% endblock %}

{% block content %}
<div class=\"p-6\">
    <div class=\"max-w-2xl mx-auto text-center\">
        <div class=\"inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-6\">
            <i data-lucide=\"construction\" class=\"w-8 h-8 text-blue-600\"></i>
        </div>
        
        <h2 class=\"text-2xl font-bold text-gray-900 mb-4\">{{ title }}</h2>
        
        <p class=\"text-gray-600 mb-8\">
            {{ description|default('Esta sección está en desarrollo. Próximamente estará disponible con todas las funcionalidades necesarias.') }}
        </p>

        <div class=\"bg-blue-50 border border-blue-200 rounded-lg p-6\">
            <h3 class=\"font-semibold text-blue-900 mb-2\">¿Qué encontrarás aquí?</h3>
            <ul class=\"text-sm text-blue-800 space-y-1\">
                <li>• Gestión completa de datos</li>
                <li>• Informes y estadísticas detalladas</li>
                <li>• Exportación de información</li>
                <li>• Interface intuitiva y moderna</li>
            </ul>
        </div>

        <div class=\"mt-8 p-4 bg-gray-50 rounded-lg\">
            <p class=\"text-sm text-gray-600\">
                <strong>Nota:</strong> El sistema está siendo desarrollado por módulos. 
                Cada sección será implementada con todas las funcionalidades requeridas.
            </p>
        </div>
    </div>
</div>
{% endblock %}", "common/coming_soon.html.twig", "C:\\xampp\\htdocs\\gesion_volunratios\\templates\\common\\coming_soon.html.twig");
    }
}
