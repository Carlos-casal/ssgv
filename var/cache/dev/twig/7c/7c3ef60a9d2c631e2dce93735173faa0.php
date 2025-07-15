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

/* service/list_service.html.twig */
class __TwigTemplate_2af0ab0417eb83171324f2ff2b392a24 extends Template
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
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "service/list_service.html.twig"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "service/list_service.html.twig"));

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

        yield "Listado de Servicios";
        
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
        yield "<div class=\"p-6 space-y-6\">
    <div class=\"flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4\">
        <div>
            <h2 class=\"text-2xl font-bold text-gray-900\">Listado de Servicios</h2>
            <p class=\"text-gray-600\">Gestiona todos los servicios programados</p>
        </div>
        <div class=\"flex gap-3\">
            ";
        // line 14
        yield "            <a href=\"";
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_service_new");
        yield "\" 
               class=\"flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors\">
                <i data-lucide=\"plus\" class=\"w-4 h-4\"></i>
                Nuevo Servicio
            </a>
        </div>
    </div>

    <div class=\"bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden\">
        <div class=\"overflow-x-auto\">
            <table class=\"w-full\">
                <thead class=\"bg-gray-50\">
                    <tr>
                        <th class=\"text-left px-6 py-3 text-sm font-medium text-gray-900\">Npº</th>
                        <th class=\"text-left px-6 py-3 text-sm font-medium text-gray-900\">Título</th>
                        <th class=\"text-left px-6 py-3 text-sm font-medium text-gray-900\">Asistentes</th>
                        <th class=\"text-left px-6 py-3 text-sm font-medium text-gray-900\">Fecha</th>
                        <th class=\"text-left px-6 py-3 text-sm font-medium text-gray-900\">Limite inscripción </th>
                        <th class=\"text-left px-6 py-3 text-sm font-medium text-gray-900\">Tipo</th>
                        <th class=\"text-left px-6 py-3 text-sm font-medium text-gray-900\">Categoria</th>
                        <th class=\"text-left px-6 py-3 text-sm font-medium text-gray-900\">Acciones</th>
                    </tr>
                </thead>
                <tbody class=\"divide-y divide-gray-200\">
                    ";
        // line 38
        if ((array_key_exists("services", $context) &&  !Twig\Extension\CoreExtension::testEmpty((isset($context["services"]) || array_key_exists("services", $context) ? $context["services"] : (function () { throw new RuntimeError('Variable "services" does not exist.', 38, $this->source); })())))) {
            // line 39
            yield "                        ";
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable((isset($context["services"]) || array_key_exists("services", $context) ? $context["services"] : (function () { throw new RuntimeError('Variable "services" does not exist.', 39, $this->source); })()));
            foreach ($context['_seq'] as $context["_key"] => $context["service"]) {
                yield " 
                            <tr class=\"hover:bg-gray-50\">
                                <td class=\"px-6 py-4\">
                                    <p class=\"font-medium text-gray-900\">";
                // line 42
                yield (((CoreExtension::getAttribute($this->env, $this->source, $context["service"], "numeration", [], "any", true, true, false, 42) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, $context["service"], "numeration", [], "any", false, false, false, 42)))) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["service"], "numeration", [], "any", false, false, false, 42), "html", null, true)) : ("N/A"));
                yield "</p>
                                </td>
                                <td class=\"px-6 py-4\">
                                    <p class=\"font-medium text-gray-900\">";
                // line 45
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["service"], "title", [], "any", false, false, false, 45), "html", null, true);
                yield "</p>
                                </td>
                                <td class=\"px-6 py-4 text-sm text-gray-900\">
                                   
                                </td>
                                <td class=\"px-6 py-4 text-sm text-gray-900\"> 
                                ";
                // line 51
                yield (((($tmp = CoreExtension::getAttribute($this->env, $this->source, $context["service"], "startDate", [], "any", false, false, false, 51)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatDate(CoreExtension::getAttribute($this->env, $this->source, $context["service"], "startDate", [], "any", false, false, false, 51), "d/m/Y H:i"), "html", null, true)) : ("N/A"));
                yield "
                                </td>
                                <td class=\"px-6 py-4 text-sm text-gray-900\">
                                ";
                // line 54
                yield (((($tmp = CoreExtension::getAttribute($this->env, $this->source, $context["service"], "registrationLimitDate", [], "any", false, false, false, 54)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Twig\Extension\CoreExtension']->formatDate(CoreExtension::getAttribute($this->env, $this->source, $context["service"], "registrationLimitDate", [], "any", false, false, false, 54), "d/m/Y H:i"), "html", null, true)) : ("N/A"));
                yield "
                                </td>
                                <td class=\"px-6 py-4 text-sm text-gray-900\">
                                ";
                // line 57
                yield (((CoreExtension::getAttribute($this->env, $this->source, $context["service"], "type", [], "any", true, true, false, 57) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, $context["service"], "type", [], "any", false, false, false, 57)))) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["service"], "type", [], "any", false, false, false, 57), "html", null, true)) : ("N/A"));
                yield "
                                </td>
                                 <td class=\"px-6 py-4 text-sm text-gray-900\">
                                ";
                // line 60
                yield (((CoreExtension::getAttribute($this->env, $this->source, $context["service"], "category", [], "any", true, true, false, 60) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, $context["service"], "category", [], "any", false, false, false, 60)))) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["service"], "category", [], "any", false, false, false, 60), "html", null, true)) : ("N/A"));
                yield "
                                </td>
                                <td class=\"px-6 py-4\">
                                    <div class=\"flex items-center gap-2\">
                                        <a href=\"";
                // line 64
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_service_show", ["id" => CoreExtension::getAttribute($this->env, $this->source, $context["service"], "id", [], "any", false, false, false, 64)]), "html", null, true);
                yield "\" class=\"p-1 text-blue-600 hover:bg-blue-50 rounded\" title=\"Ver\">
                                            <i data-lucide=\"eye\" class=\"w-4 h-4\"></i>
                                        </a>
                                        <a href=\"";
                // line 67
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_service_edit", ["id" => CoreExtension::getAttribute($this->env, $this->source, $context["service"], "id", [], "any", false, false, false, 67)]), "html", null, true);
                yield "\"
                                        class=\"p-1 text-green-600 hover:bg-green-50 rounded\" title=\"Editar\">
                                            <i data-lucide=\"edit\" class=\"w-4 h-4\"></i>
                                        </a>
                                        <form method=\"post\" action=\"#\" onsubmit=\"return confirm('¿Estás seguro de que quieres eliminar este servicio?');\" style=\"display:inline;\">
                                            ";
                // line 73
                yield "                                            <button type=\"submit\" class=\"p-1 text-red-600 hover:bg-red-50 rounded\" title=\"Eliminar (ruta pendiente)\">
                                                <i data-lucide=\"trash-2\" class=\"w-4 h-4\"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_key'], $context['service'], $context['_parent']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 81
            yield "                    ";
        } else {
            // line 82
            yield "                        <tr>
                            <td colspan=\"7\" class=\"px-6 py-8 text-center text-gray-500\">
                                No se encontraron servicios.
                            </td>
                        </tr>
                    ";
        }
        // line 88
        yield "                </tbody>
            </table>
        </div>
        ";
        // line 92
        yield "        ";
        if (((array_key_exists("services", $context) && CoreExtension::getAttribute($this->env, $this->source, ($context["services"] ?? null), "getTotalItemCount", [], "any", true, true, false, 92)) && (CoreExtension::getAttribute($this->env, $this->source, (isset($context["services"]) || array_key_exists("services", $context) ? $context["services"] : (function () { throw new RuntimeError('Variable "services" does not exist.', 92, $this->source); })()), "count", [], "any", false, false, false, 92) > 0))) {
            // line 93
            yield "            <div class=\"navigation flex justify-center py-4 bg-gray-50 border-t border-gray-200\">
                ";
            // line 94
            yield $this->env->getRuntime('Knp\Bundle\PaginatorBundle\Twig\Extension\PaginationRuntime')->render($this->env, (isset($context["services"]) || array_key_exists("services", $context) ? $context["services"] : (function () { throw new RuntimeError('Variable "services" does not exist.', 94, $this->source); })()));
            yield "
            </div>
        ";
        }
        // line 97
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
        return "service/list_service.html.twig";
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
        return array (  243 => 97,  237 => 94,  234 => 93,  231 => 92,  226 => 88,  218 => 82,  215 => 81,  202 => 73,  194 => 67,  188 => 64,  181 => 60,  175 => 57,  169 => 54,  163 => 51,  154 => 45,  148 => 42,  139 => 39,  137 => 38,  109 => 14,  100 => 6,  87 => 5,  64 => 3,  41 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("{% extends 'layout/app.html.twig' %}

{% block page_title %}Listado de Servicios{% endblock %}

{% block content %}
<div class=\"p-6 space-y-6\">
    <div class=\"flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4\">
        <div>
            <h2 class=\"text-2xl font-bold text-gray-900\">Listado de Servicios</h2>
            <p class=\"text-gray-600\">Gestiona todos los servicios programados</p>
        </div>
        <div class=\"flex gap-3\">
            {# Asegúrate de que la ruta 'app_service_new' exista y esté correctamente definida #}
            <a href=\"{{ path('app_service_new') }}\" 
               class=\"flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors\">
                <i data-lucide=\"plus\" class=\"w-4 h-4\"></i>
                Nuevo Servicio
            </a>
        </div>
    </div>

    <div class=\"bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden\">
        <div class=\"overflow-x-auto\">
            <table class=\"w-full\">
                <thead class=\"bg-gray-50\">
                    <tr>
                        <th class=\"text-left px-6 py-3 text-sm font-medium text-gray-900\">Npº</th>
                        <th class=\"text-left px-6 py-3 text-sm font-medium text-gray-900\">Título</th>
                        <th class=\"text-left px-6 py-3 text-sm font-medium text-gray-900\">Asistentes</th>
                        <th class=\"text-left px-6 py-3 text-sm font-medium text-gray-900\">Fecha</th>
                        <th class=\"text-left px-6 py-3 text-sm font-medium text-gray-900\">Limite inscripción </th>
                        <th class=\"text-left px-6 py-3 text-sm font-medium text-gray-900\">Tipo</th>
                        <th class=\"text-left px-6 py-3 text-sm font-medium text-gray-900\">Categoria</th>
                        <th class=\"text-left px-6 py-3 text-sm font-medium text-gray-900\">Acciones</th>
                    </tr>
                </thead>
                <tbody class=\"divide-y divide-gray-200\">
                    {% if services is defined and services is not empty %}
                        {% for service in services %} 
                            <tr class=\"hover:bg-gray-50\">
                                <td class=\"px-6 py-4\">
                                    <p class=\"font-medium text-gray-900\">{{ service.numeration ?? 'N/A' }}</p>
                                </td>
                                <td class=\"px-6 py-4\">
                                    <p class=\"font-medium text-gray-900\">{{ service.title }}</p>
                                </td>
                                <td class=\"px-6 py-4 text-sm text-gray-900\">
                                   
                                </td>
                                <td class=\"px-6 py-4 text-sm text-gray-900\"> 
                                {{ service.startDate ? service.startDate|date('d/m/Y H:i') : 'N/A' }}
                                </td>
                                <td class=\"px-6 py-4 text-sm text-gray-900\">
                                {{ service.registrationLimitDate ? service.registrationLimitDate |date('d/m/Y H:i') : 'N/A' }}
                                </td>
                                <td class=\"px-6 py-4 text-sm text-gray-900\">
                                {{ service.type ?? 'N/A' }}
                                </td>
                                 <td class=\"px-6 py-4 text-sm text-gray-900\">
                                {{ service.category ?? 'N/A' }}
                                </td>
                                <td class=\"px-6 py-4\">
                                    <div class=\"flex items-center gap-2\">
                                        <a href=\"{{ path('app_service_show', {'id': service.id}) }}\" class=\"p-1 text-blue-600 hover:bg-blue-50 rounded\" title=\"Ver\">
                                            <i data-lucide=\"eye\" class=\"w-4 h-4\"></i>
                                        </a>
                                        <a href=\"{{ path('app_service_edit', {'id': service.id}) }}\"
                                        class=\"p-1 text-green-600 hover:bg-green-50 rounded\" title=\"Editar\">
                                            <i data-lucide=\"edit\" class=\"w-4 h-4\"></i>
                                        </a>
                                        <form method=\"post\" action=\"#\" onsubmit=\"return confirm('¿Estás seguro de que quieres eliminar este servicio?');\" style=\"display:inline;\">
                                            {# <input type=\"hidden\" name=\"_token\" value=\"{{ csrf_token('delete' ~ service.id) }}\"> #}
                                            <button type=\"submit\" class=\"p-1 text-red-600 hover:bg-red-50 rounded\" title=\"Eliminar (ruta pendiente)\">
                                                <i data-lucide=\"trash-2\" class=\"w-4 h-4\"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        {% endfor %}
                    {% else %}
                        <tr>
                            <td colspan=\"7\" class=\"px-6 py-8 text-center text-gray-500\">
                                No se encontraron servicios.
                            </td>
                        </tr>
                    {% endif %}
                </tbody>
            </table>
        </div>
        {# Paginación (si aplica) #}
        {% if services is defined and services.getTotalItemCount is defined and services.count > 0 %}
            <div class=\"navigation flex justify-center py-4 bg-gray-50 border-t border-gray-200\">
                {{ knp_pagination_render(services) }}
            </div>
        {% endif %}
    </div>
</div>
{% endblock %}", "service/list_service.html.twig", "C:\\xampp\\htdocs\\gesion_volunratios\\templates\\service\\list_service.html.twig");
    }
}
