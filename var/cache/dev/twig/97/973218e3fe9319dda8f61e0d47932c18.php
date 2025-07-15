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

/* volunteer/list.html.twig */
class __TwigTemplate_1f78eeda98f79cd2eca06fc4515a0468 extends Template
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
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->enter($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "volunteer/list.html.twig"));

        $__internal_6f47bbe9983af81f1e7450e9a3e3768f = $this->extensions["Symfony\\Bridge\\Twig\\Extension\\ProfilerExtension"];
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->enter($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof = new \Twig\Profiler\Profile($this->getTemplateName(), "template", "volunteer/list.html.twig"));

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

        yield "Listado de Personal";
        
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
            <h2 class=\"text-2xl font-bold text-gray-900\">Listado de Personal</h2>
            <p class=\"text-gray-600\">Gestiona toda la información de voluntarios</p>
        </div>
        <div class=\"flex gap-3\">
            <a href=\"";
        // line 13
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_volunteer_export_csv");
        yield "\" 
               class=\"flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors\">
                <i data-lucide=\"download\" class=\"w-4 h-4\"></i>
                Exportar CSV
            </a>
            <a href=\"#\" 
               class=\"flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors\">
                <i data-lucide=\"plus\" class=\"w-4 h-4\"></i>
                Nuevo Voluntario
            </a>
        </div>
    </div>

    <div class=\"bg-white rounded-xl p-6 shadow-sm border border-gray-200\">
        <form method=\"GET\" class=\"flex flex-col lg:flex-row gap-4\">
            <div class=\"flex-1\">
                <div class=\"relative\">
                    <i data-lucide=\"search\" class=\"absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400\"></i>
                    <input
                        type=\"text\"
                        name=\"search\"
                        value=\"";
        // line 34
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((isset($context["searchTerm"]) || array_key_exists("searchTerm", $context) ? $context["searchTerm"] : (function () { throw new RuntimeError('Variable "searchTerm" does not exist.', 34, $this->source); })()), "html", null, true);
        yield "\"
                        placeholder=\"Buscar por nombre o email...\"
                        class=\"w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500\"
                    />
                </div>
            </div>
            <div class=\"flex gap-3\">
                <select name=\"status\" class=\"px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500\">
                    <option value=\"all\" ";
        // line 42
        yield ((((isset($context["filterStatus"]) || array_key_exists("filterStatus", $context) ? $context["filterStatus"] : (function () { throw new RuntimeError('Variable "filterStatus" does not exist.', 42, $this->source); })()) == "all")) ? ("selected") : (""));
        yield ">Todos los estados</option>
                    <option value=\"Activo\" ";
        // line 43
        yield ((((isset($context["filterStatus"]) || array_key_exists("filterStatus", $context) ? $context["filterStatus"] : (function () { throw new RuntimeError('Variable "filterStatus" does not exist.', 43, $this->source); })()) == "Activo")) ? ("selected") : (""));
        yield ">Activos</option> ";
        // line 44
        yield "                    <option value=\"Suspensión\" ";
        yield ((((isset($context["filterStatus"]) || array_key_exists("filterStatus", $context) ? $context["filterStatus"] : (function () { throw new RuntimeError('Variable "filterStatus" does not exist.', 44, $this->source); })()) == "Suspensión")) ? ("selected") : (""));
        yield ">En Suspensión</option> ";
        // line 45
        yield "                    <option value=\"Baja\" ";
        yield ((((isset($context["filterStatus"]) || array_key_exists("filterStatus", $context) ? $context["filterStatus"] : (function () { throw new RuntimeError('Variable "filterStatus" does not exist.', 45, $this->source); })()) == "Baja")) ? ("selected") : (""));
        yield ">De Baja</option> ";
        // line 46
        yield "                </select>
                <button type=\"submit\" class=\"flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors\">
                    <i data-lucide=\"filter\" class=\"w-4 h-4\"></i>
                    Filtrar
                </button>
            </div>
        </form>
    </div>

    <div class=\"grid grid-cols-1 md:grid-cols-4 gap-4\">
        <div class=\"bg-white rounded-lg p-4 shadow-sm border border-gray-200\">
            <p class=\"text-sm text-gray-600\">Total Voluntarios</p>
            <p class=\"text-2xl font-bold text-gray-900\">";
        // line 58
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, (isset($context["stats"]) || array_key_exists("stats", $context) ? $context["stats"] : (function () { throw new RuntimeError('Variable "stats" does not exist.', 58, $this->source); })()), "total", [], "any", false, false, false, 58), "html", null, true);
        yield "</p>
        </div>
        <div class=\"bg-white rounded-lg p-4 shadow-sm border border-gray-200\">
            <p class=\"text-sm text-gray-600\">Activos</p>
            <p class=\"text-2xl font-bold text-green-600\">";
        // line 62
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, (isset($context["stats"]) || array_key_exists("stats", $context) ? $context["stats"] : (function () { throw new RuntimeError('Variable "stats" does not exist.', 62, $this->source); })()), "Activo", [], "any", false, false, false, 62), "html", null, true);
        yield "</p>
        </div>
        <div class=\"bg-white rounded-lg p-4 shadow-sm border border-gray-200\">
            <p class=\"text-sm text-gray-600\">En Suspensión</p> ";
        // line 66
        yield "            <p class=\"text-2xl font-bold text-orange-600\">";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, (isset($context["stats"]) || array_key_exists("stats", $context) ? $context["stats"] : (function () { throw new RuntimeError('Variable "stats" does not exist.', 66, $this->source); })()), "Suspensión", [], "any", false, false, false, 66), "html", null, true);
        yield "</p>
        </div>
        <div class=\"bg-white rounded-lg p-4 shadow-sm border border-gray-200\">
            <p class=\"text-sm text-gray-600\">De Baja</p> ";
        // line 70
        yield "            <p class=\"text-2xl font-bold text-red-600\">";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, (isset($context["stats"]) || array_key_exists("stats", $context) ? $context["stats"] : (function () { throw new RuntimeError('Variable "stats" does not exist.', 70, $this->source); })()), "Baja", [], "any", false, false, false, 70), "html", null, true);
        yield "</p>
        </div>
    </div>

    <div class=\"bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden\">
        <div class=\"overflow-x-auto\">
            <table class=\"w-full\">
                <thead class=\"bg-gray-50\">
                    <tr>
                        <th class=\"text-left px-6 py-3 text-sm font-medium text-gray-900\">Voluntario</th>
                        <th class=\"text-left px-6 py-3 text-sm font-medium text-gray-900\">Contacto</th>
                        <th class=\"text-left px-6 py-3 text-sm font-medium text-gray-900\">Carnet de conducir</th>
                        <th class=\"text-left px-6 py-3 text-sm font-medium text-gray-900\">Titulacion Sanitaria</th>
                        <th class=\"text-left px-6 py-3 text-sm font-medium text-gray-900\">Permiso de Navegación</th>
                        <th class=\"text-left px-6 py-3 text-sm font-medium text-gray-900\">Acciones</th>
                    </tr>
                </thead>
                <tbody class=\"divide-y divide-gray-200\">
                    ";
        // line 89
        yield "                    ";
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable((isset($context["pagination"]) || array_key_exists("pagination", $context) ? $context["pagination"] : (function () { throw new RuntimeError('Variable "pagination" does not exist.', 89, $this->source); })()));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["_key"] => $context["volunteer"]) {
            yield " 
                        <tr class=\"hover:bg-gray-50\">
                            <td class=\"px-6 py-4\">
                                <div class=\"flex items-center gap-3\">
                                    <div class=\"w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center\">
                                        <span class=\"text-blue-600 font-medium\">
                                            ";
            // line 95
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::upper($this->env->getCharset(), Twig\Extension\CoreExtension::slice($this->env->getCharset(), Twig\Extension\CoreExtension::join(Twig\Extension\CoreExtension::map($this->env, Twig\Extension\CoreExtension::split($this->env->getCharset(), CoreExtension::getAttribute($this->env, $this->source, $context["volunteer"], "name", [], "any", false, false, false, 95), " "), function ($__name__) use ($context, $macros) { $context["name"] = $__name__; return Twig\Extension\CoreExtension::first($this->env->getCharset(), (isset($context["name"]) || array_key_exists("name", $context) ? $context["name"] : (function () { throw new RuntimeError('Variable "name" does not exist.', 95, $this->source); })())); }), ""), 0, 2)), "html", null, true);
            yield "
                                        </span>
                                    </div>
                                    <div>
                                        <p class=\"font-medium text-gray-900\"> ";
            // line 99
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["volunteer"], "name", [], "any", false, false, false, 99), "html", null, true);
            yield "</p>
                                        <p class=\"text-sm text-gray-500\">ID: ";
            // line 100
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["volunteer"], "id", [], "any", false, false, false, 100), "html", null, true);
            yield "</p>
                                    </div>
                                </div>
                            </td>
                            <td class=\"px-6 py-4\">
                                <div>
                                    <p class=\"text-sm text-gray-900\">";
            // line 106
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["volunteer"], "phone", [], "any", false, false, false, 106), "html", null, true);
            yield "</p>
                                    ";
            // line 108
            yield "                                    <p class=\"text-sm text-gray-500\">";
            yield (((($tmp = CoreExtension::getAttribute($this->env, $this->source, $context["volunteer"], "user", [], "any", false, false, false, 108)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["volunteer"], "user", [], "any", false, false, false, 108), "email", [], "any", false, false, false, 108), "html", null, true)) : ("N/A"));
            yield "</p> 
                                </div>
                            </td>
                            <td class=\"px-6 py-4\">
                                <p class=\"font-medium text-gray-900\">
                                    ";
            // line 113
            if ((($tmp =  !Twig\Extension\CoreExtension::testEmpty(CoreExtension::getAttribute($this->env, $this->source, $context["volunteer"], "drivingLicenses", [], "any", false, false, false, 113))) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                // line 114
                yield "                                        ";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::join(CoreExtension::getAttribute($this->env, $this->source, $context["volunteer"], "drivingLicenses", [], "any", false, false, false, 114), ", "), "html", null, true);
                yield "
                                    ";
            } else {
                // line 116
                yield "                                        N/A ";
                // line 117
                yield "                                    ";
            }
            // line 118
            yield "                                </p>
                            </td>
                            <td class=\"px-6 py-4 text-sm text-gray-900\">
                                <p class=\"font-medium text-gray-900\">
                                    ";
            // line 122
            if ((($tmp =  !Twig\Extension\CoreExtension::testEmpty(CoreExtension::getAttribute($this->env, $this->source, $context["volunteer"], "specificQualifications", [], "any", false, false, false, 122))) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                // line 123
                yield "                                        ";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::join(CoreExtension::getAttribute($this->env, $this->source, $context["volunteer"], "specificQualifications", [], "any", false, false, false, 123), ", "), "html", null, true);
                yield "
                                    ";
            } else {
                // line 125
                yield "                                        N/A
                                    ";
            }
            // line 127
            yield "                                </p>
                            </td>
                            <td class=\"px-6 py-4\">
                                <p class=\"font-medium text-gray-900\">
                                    ";
            // line 131
            if ((($tmp =  !Twig\Extension\CoreExtension::testEmpty(CoreExtension::getAttribute($this->env, $this->source, $context["volunteer"], "navigationLicenses", [], "any", false, false, false, 131))) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                // line 132
                yield "                                        ";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::join(CoreExtension::getAttribute($this->env, $this->source, $context["volunteer"], "navigationLicenses", [], "any", false, false, false, 132), ", "), "html", null, true);
                yield "
                                    ";
            } else {
                // line 134
                yield "                                        N/A
                                    ";
            }
            // line 136
            yield "                                </p>
                            </td>
                            <td class=\"px-6 py-4\">
                                <div class=\"flex items-center gap-2\">
                                    <button class=\"p-1 text-blue-600 hover:bg-blue-50 rounded\">
                                        <i data-lucide=\"eye\" class=\"w-4 h-4\"></i>
                                    </button>
                                    <a href=\"";
            // line 143
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_volunteer_edit", ["id" => CoreExtension::getAttribute($this->env, $this->source, $context["volunteer"], "id", [], "any", false, false, false, 143)]), "html", null, true);
            yield "\"
                                    class=\"p-1 text-green-600 hover:bg-green-50 rounded\">
                                        <i data-lucide=\"edit\" class=\"w-4 h-4\"></i>
                                    </a>
                                    <button class=\"p-1 text-red-600 hover:bg-red-50 rounded\">
                                        <i data-lucide=\"trash-2\" class=\"w-4 h-4\"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    ";
            $context['_iterated'] = true;
        }
        // line 153
        if (!$context['_iterated']) {
            // line 154
            yield "                        <tr>
                            <td colspan=\"7\" class=\"px-6 py-8 text-center text-gray-500\">
                                No se encontraron voluntarios
                            </td>
                        </tr>
                    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['volunteer'], $context['_parent'], $context['_iterated']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 160
        yield "                </tbody>
            </table>
        </div>

        ";
        // line 165
        yield "        <div class=\"navigation flex justify-center py-4 bg-gray-50 border-t border-gray-200\">
            ";
        // line 166
        yield $this->env->getRuntime('Knp\Bundle\PaginatorBundle\Twig\Extension\PaginationRuntime')->render($this->env, (isset($context["pagination"]) || array_key_exists("pagination", $context) ? $context["pagination"] : (function () { throw new RuntimeError('Variable "pagination" does not exist.', 166, $this->source); })()));
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
        return "volunteer/list.html.twig";
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
        return array (  356 => 166,  353 => 165,  347 => 160,  336 => 154,  334 => 153,  319 => 143,  310 => 136,  306 => 134,  300 => 132,  298 => 131,  292 => 127,  288 => 125,  282 => 123,  280 => 122,  274 => 118,  271 => 117,  269 => 116,  263 => 114,  261 => 113,  252 => 108,  248 => 106,  239 => 100,  235 => 99,  228 => 95,  215 => 89,  193 => 70,  186 => 66,  180 => 62,  173 => 58,  159 => 46,  155 => 45,  151 => 44,  148 => 43,  144 => 42,  133 => 34,  109 => 13,  100 => 6,  87 => 5,  64 => 3,  41 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("{% extends 'layout/app.html.twig' %}

{% block page_title %}Listado de Personal{% endblock %}

{% block content %}
<div class=\"p-6 space-y-6\">
    <div class=\"flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4\">
        <div>
            <h2 class=\"text-2xl font-bold text-gray-900\">Listado de Personal</h2>
            <p class=\"text-gray-600\">Gestiona toda la información de voluntarios</p>
        </div>
        <div class=\"flex gap-3\">
            <a href=\"{{ path('app_volunteer_export_csv') }}\" 
               class=\"flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors\">
                <i data-lucide=\"download\" class=\"w-4 h-4\"></i>
                Exportar CSV
            </a>
            <a href=\"#\" 
               class=\"flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors\">
                <i data-lucide=\"plus\" class=\"w-4 h-4\"></i>
                Nuevo Voluntario
            </a>
        </div>
    </div>

    <div class=\"bg-white rounded-xl p-6 shadow-sm border border-gray-200\">
        <form method=\"GET\" class=\"flex flex-col lg:flex-row gap-4\">
            <div class=\"flex-1\">
                <div class=\"relative\">
                    <i data-lucide=\"search\" class=\"absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400\"></i>
                    <input
                        type=\"text\"
                        name=\"search\"
                        value=\"{{ searchTerm }}\"
                        placeholder=\"Buscar por nombre o email...\"
                        class=\"w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500\"
                    />
                </div>
            </div>
            <div class=\"flex gap-3\">
                <select name=\"status\" class=\"px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500\">
                    <option value=\"all\" {{ filterStatus == 'all' ? 'selected' : '' }}>Todos los estados</option>
                    <option value=\"Activo\" {{ filterStatus == 'Activo' ? 'selected' : '' }}>Activos</option> {# Asegúrate de que los valores coincidan con los de tu entidad #}
                    <option value=\"Suspensión\" {{ filterStatus == 'Suspensión' ? 'selected' : '' }}>En Suspensión</option> {# Actualizado para reflejar mejor los estados #}
                    <option value=\"Baja\" {{ filterStatus == 'Baja' ? 'selected' : '' }}>De Baja</option> {# Actualizado para reflejar mejor los estados #}
                </select>
                <button type=\"submit\" class=\"flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors\">
                    <i data-lucide=\"filter\" class=\"w-4 h-4\"></i>
                    Filtrar
                </button>
            </div>
        </form>
    </div>

    <div class=\"grid grid-cols-1 md:grid-cols-4 gap-4\">
        <div class=\"bg-white rounded-lg p-4 shadow-sm border border-gray-200\">
            <p class=\"text-sm text-gray-600\">Total Voluntarios</p>
            <p class=\"text-2xl font-bold text-gray-900\">{{ stats.total }}</p>
        </div>
        <div class=\"bg-white rounded-lg p-4 shadow-sm border border-gray-200\">
            <p class=\"text-sm text-gray-600\">Activos</p>
            <p class=\"text-2xl font-bold text-green-600\">{{ stats.Activo }}</p>
        </div>
        <div class=\"bg-white rounded-lg p-4 shadow-sm border border-gray-200\">
            <p class=\"text-sm text-gray-600\">En Suspensión</p> {# Actualizado #}
            <p class=\"text-2xl font-bold text-orange-600\">{{ stats.Suspensión }}</p>
        </div>
        <div class=\"bg-white rounded-lg p-4 shadow-sm border border-gray-200\">
            <p class=\"text-sm text-gray-600\">De Baja</p> {# Actualizado #}
            <p class=\"text-2xl font-bold text-red-600\">{{ stats.Baja }}</p>
        </div>
    </div>

    <div class=\"bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden\">
        <div class=\"overflow-x-auto\">
            <table class=\"w-full\">
                <thead class=\"bg-gray-50\">
                    <tr>
                        <th class=\"text-left px-6 py-3 text-sm font-medium text-gray-900\">Voluntario</th>
                        <th class=\"text-left px-6 py-3 text-sm font-medium text-gray-900\">Contacto</th>
                        <th class=\"text-left px-6 py-3 text-sm font-medium text-gray-900\">Carnet de conducir</th>
                        <th class=\"text-left px-6 py-3 text-sm font-medium text-gray-900\">Titulacion Sanitaria</th>
                        <th class=\"text-left px-6 py-3 text-sm font-medium text-gray-900\">Permiso de Navegación</th>
                        <th class=\"text-left px-6 py-3 text-sm font-medium text-gray-900\">Acciones</th>
                    </tr>
                </thead>
                <tbody class=\"divide-y divide-gray-200\">
                    {# CAMBIO CLAVE AQUÍ: Iterar sobre 'pagination' en lugar de 'volunteers' #}
                    {% for volunteer in pagination %} 
                        <tr class=\"hover:bg-gray-50\">
                            <td class=\"px-6 py-4\">
                                <div class=\"flex items-center gap-3\">
                                    <div class=\"w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center\">
                                        <span class=\"text-blue-600 font-medium\">
                                            {{ volunteer.name|split(' ')|map(name => name|first)|join('')|slice(0, 2)|upper }}
                                        </span>
                                    </div>
                                    <div>
                                        <p class=\"font-medium text-gray-900\"> {{ volunteer.name }}</p>
                                        <p class=\"text-sm text-gray-500\">ID: {{ volunteer.id }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class=\"px-6 py-4\">
                                <div>
                                    <p class=\"text-sm text-gray-900\">{{ volunteer.phone }}</p>
                                    {# Asegúrate de que volunteer.user.email esté disponible si user puede ser null #}
                                    <p class=\"text-sm text-gray-500\">{{ volunteer.user ? volunteer.user.email : 'N/A' }}</p> 
                                </div>
                            </td>
                            <td class=\"px-6 py-4\">
                                <p class=\"font-medium text-gray-900\">
                                    {% if volunteer.drivingLicenses is not empty %}
                                        {{ volunteer.drivingLicenses|join(', ') }}
                                    {% else %}
                                        N/A {# Puedes poner un guión, \"Ninguno\", etc. #}
                                    {% endif %}
                                </p>
                            </td>
                            <td class=\"px-6 py-4 text-sm text-gray-900\">
                                <p class=\"font-medium text-gray-900\">
                                    {% if volunteer.specificQualifications is not empty %}
                                        {{ volunteer.specificQualifications|join(', ') }}
                                    {% else %}
                                        N/A
                                    {% endif %}
                                </p>
                            </td>
                            <td class=\"px-6 py-4\">
                                <p class=\"font-medium text-gray-900\">
                                    {% if volunteer.navigationLicenses is not empty %}
                                        {{ volunteer.navigationLicenses|join(', ') }}
                                    {% else %}
                                        N/A
                                    {% endif %}
                                </p>
                            </td>
                            <td class=\"px-6 py-4\">
                                <div class=\"flex items-center gap-2\">
                                    <button class=\"p-1 text-blue-600 hover:bg-blue-50 rounded\">
                                        <i data-lucide=\"eye\" class=\"w-4 h-4\"></i>
                                    </button>
                                    <a href=\"{{ path('app_volunteer_edit', {'id': volunteer.id}) }}\"
                                    class=\"p-1 text-green-600 hover:bg-green-50 rounded\">
                                        <i data-lucide=\"edit\" class=\"w-4 h-4\"></i>
                                    </a>
                                    <button class=\"p-1 text-red-600 hover:bg-red-50 rounded\">
                                        <i data-lucide=\"trash-2\" class=\"w-4 h-4\"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    {% else %}
                        <tr>
                            <td colspan=\"7\" class=\"px-6 py-8 text-center text-gray-500\">
                                No se encontraron voluntarios
                            </td>
                        </tr>
                    {% endfor %}
                </tbody>
            </table>
        </div>

        {# CAMBIO CLAVE AQUÍ: Renderizar los controles de paginación #}
        <div class=\"navigation flex justify-center py-4 bg-gray-50 border-t border-gray-200\">
            {{ knp_pagination_render(pagination) }}
        </div>
    </div>
</div>
{% endblock %}", "volunteer/list.html.twig", "C:\\xampp\\htdocs\\gesion_volunratios\\templates\\volunteer\\list.html.twig");
    }
}
