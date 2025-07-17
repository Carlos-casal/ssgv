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

/* dashboard/volunteer_dashboard.html.twig */
class __TwigTemplate_688bdb9ea57b2dc216dbabfa2bf30e99 extends Template
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
        return "layout/app.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $__internal_5a27a8ba21ca79b61932376b2fa922d2 = $this->extensions["Symfony\\Bundle\\WebProfilerBundle\\Twig\\WebProfilerExtension"];
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "dashboard/volunteer_dashboard.html.twig"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "dashboard/volunteer_dashboard.html.twig"));

        $this->parent = $this->load("layout/app.html.twig", 1);
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

        yield "Listado de sercicos";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        yield from [];
    }

    // line 6
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

        // line 7
        yield "<div class=\"p-6 space-y-6\">
    <div class=\"flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4\">
        <div>
            <h2 class=\"text-2xl font-bold text-gray-900\">Listado de Servicios</h2>
 

<div class=\"container-fluid\">
    <h1 class=\"h3 mb-4 text-gray-800\">Servicios Disponibles</h1>

    <div class=\"card shadow mb-4\">
        <div class=\"card-header py-3\">
            <h6 class=\"m-0 font-weight-bold text-primary\">Lista de Servicios</h6>
        </div>
        <div class=\"card-body\">
            <div class=\"table-responsive\">
                <table class=\"table table-bordered\" id=\"dataTable\" width=\"100%\" cellspacing=\"0\">
                    <thead>
                        <tr>
                            <th>Nombre del Servicio</th>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        ";
        // line 32
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable((isset($context["services"]) || array_key_exists("services", $context) ? $context["services"] : (function () { throw new RuntimeError('Variable "services" does not exist.', 32, $this->source); })()));
        foreach ($context['_seq'] as $context["_key"] => $context["service"]) {
            // line 33
            yield "                        <tr>
                            <td>";
            // line 34
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["service"], "title", [], "any", false, false, false, 34), "html", null, true);
            yield "</td>
                            <td>";
            // line 35
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatDate(CoreExtension::getAttribute($this->env, $this->source, $context["service"], "startDate", [], "any", false, false, false, 35), "d/m/Y"), "html", null, true);
            yield "</td>
                            <td>";
            // line 36
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatDate(CoreExtension::getAttribute($this->env, $this->source, $context["service"], "registrationLimitDate", [], "any", false, false, false, 36), "H:i"), "html", null, true);
            yield "</td>
                            <td>
                                ";
            // line 38
            if ((CoreExtension::getAttribute($this->env, $this->source, ($context["assistanceByService"] ?? null), CoreExtension::getAttribute($this->env, $this->source, $context["service"], "id", [], "any", false, false, false, 38), [], "array", true, true, false, 38) && CoreExtension::getAttribute($this->env, $this->source, (isset($context["assistanceByService"]) || array_key_exists("assistanceByService", $context) ? $context["assistanceByService"] : (function () { throw new RuntimeError('Variable "assistanceByService" does not exist.', 38, $this->source); })()), CoreExtension::getAttribute($this->env, $this->source, $context["service"], "id", [], "any", false, false, false, 38), [], "array", false, false, false, 38))) {
                // line 39
                yield "                                    <a href=\"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_service_unattend", ["id" => CoreExtension::getAttribute($this->env, $this->source, $context["service"], "id", [], "any", false, false, false, 39)]), "html", null, true);
                yield "\" class=\"btn btn-danger btn-sm\">No Asisto</a>
                                ";
            } else {
                // line 41
                yield "                                    <a href=\"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_service_attend", ["id" => CoreExtension::getAttribute($this->env, $this->source, $context["service"], "id", [], "any", false, false, false, 41)]), "html", null, true);
                yield "\" class=\"btn btn-success btn-sm\">Asisto</a>
                                ";
            }
            // line 43
            yield "                            </td>
                        </tr>
                        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['service'], $context['_parent']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 46
        yield "                    </tbody>
                </table>
            </div>
 
        </div>
    </div>

    
        ";
        // line 55
        yield "        ";
        if (((array_key_exists("services", $context) && CoreExtension::getAttribute($this->env, $this->source, ($context["services"] ?? null), "getTotalItemCount", [], "any", true, true, false, 55)) && (CoreExtension::getAttribute($this->env, $this->source, (isset($context["services"]) || array_key_exists("services", $context) ? $context["services"] : (function () { throw new RuntimeError('Variable "services" does not exist.', 55, $this->source); })()), "count", [], "any", false, false, false, 55) > 0))) {
            // line 56
            yield "            <div class=\"navigation flex justify-center py-4 bg-gray-50 border-t border-gray-200\">
                ";
            // line 57
            yield $this->env->getRuntime('Knp\Bundle\PaginatorBundle\Twig\Extension\PaginationRuntime')->render($this->env, (isset($context["services"]) || array_key_exists("services", $context) ? $context["services"] : (function () { throw new RuntimeError('Variable "services" does not exist.', 57, $this->source); })()));
            yield "
            </div>
        ";
        }
        // line 60
        yield "    </div>
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
        return "dashboard/volunteer_dashboard.html.twig";
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
        return array (  191 => 60,  185 => 57,  182 => 56,  179 => 55,  169 => 46,  161 => 43,  155 => 41,  149 => 39,  147 => 38,  142 => 36,  138 => 35,  134 => 34,  131 => 33,  127 => 32,  100 => 7,  87 => 6,  64 => 3,  41 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("{% extends 'layout/app.html.twig' %}

{% block title %}Listado de sercicos{% endblock %}

 
{% block body %}
<div class=\"p-6 space-y-6\">
    <div class=\"flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4\">
        <div>
            <h2 class=\"text-2xl font-bold text-gray-900\">Listado de Servicios</h2>
 

<div class=\"container-fluid\">
    <h1 class=\"h3 mb-4 text-gray-800\">Servicios Disponibles</h1>

    <div class=\"card shadow mb-4\">
        <div class=\"card-header py-3\">
            <h6 class=\"m-0 font-weight-bold text-primary\">Lista de Servicios</h6>
        </div>
        <div class=\"card-body\">
            <div class=\"table-responsive\">
                <table class=\"table table-bordered\" id=\"dataTable\" width=\"100%\" cellspacing=\"0\">
                    <thead>
                        <tr>
                            <th>Nombre del Servicio</th>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        {% for service in services %}
                        <tr>
                            <td>{{ service.title }}</td>
                            <td>{{ service.startDate|date('d/m/Y') }}</td>
                            <td>{{ service.registrationLimitDate|date('H:i') }}</td>
                            <td>
                                {% if assistanceByService[service.id] is defined and assistanceByService[service.id] %}
                                    <a href=\"{{ path('app_service_unattend', {'id': service.id}) }}\" class=\"btn btn-danger btn-sm\">No Asisto</a>
                                {% else %}
                                    <a href=\"{{ path('app_service_attend', {'id': service.id}) }}\" class=\"btn btn-success btn-sm\">Asisto</a>
                                {% endif %}
                            </td>
                        </tr>
                        {% endfor %}
                    </tbody>
                </table>
            </div>
 
        </div>
    </div>

    
        {# Paginación (si aplica) #}
        {% if services is defined and services.getTotalItemCount is defined and services.count > 0 %}
            <div class=\"navigation flex justify-center py-4 bg-gray-50 border-t border-gray-200\">
                {{ knp_pagination_render(services) }}
            </div>
        {% endif %}
    </div>
</div>
{% endblock %}", "dashboard/volunteer_dashboard.html.twig", "C:\\xampp\\htdocs\\gesion_volunratios\\templates\\dashboard\\volunteer_dashboard.html.twig");
    }
}
