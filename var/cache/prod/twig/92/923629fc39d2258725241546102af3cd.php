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
class __TwigTemplate_c9b430cf66b36dff75370c9600f8764a extends Template
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
        $this->parent = $this->load("layout/app.html.twig", 1);
        yield from $this->parent->unwrap()->yield($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_page_title(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        yield "Listado de Personal";
        yield from [];
    }

    // line 5
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_content(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 6
        yield "<div class=\"p-6 space-y-6\">
    <!-- Header -->
    <div class=\"flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4\">
        <div>
            <h2 class=\"text-2xl font-bold text-gray-900\">Listado de Personal</h2>
            <p class=\"text-gray-600\">Gestiona toda la información de voluntarios</p>
        </div>
        <div class=\"flex gap-3\">
            <a href=\"";
        // line 14
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_volunteer_export_csv");
        yield "\" 
               class=\"flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors\">
                <i data-lucide=\"download\" class=\"w-4 h-4\"></i>
                Exportar CSV
            </a>
            <a href=\"";
        // line 19
        yield $this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_volunteer_new");
        yield "\" 
               class=\"flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors\">
                <i data-lucide=\"plus\" class=\"w-4 h-4\"></i>
                Nuevo Voluntario
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class=\"bg-white rounded-xl p-6 shadow-sm border border-gray-200\">
        <form method=\"GET\" class=\"flex flex-col lg:flex-row gap-4\">
            <div class=\"flex-1\">
                <div class=\"relative\">
                    <i data-lucide=\"search\" class=\"absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400\"></i>
                    <input
                        type=\"text\"
                        name=\"search\"
                        value=\"";
        // line 36
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(($context["searchTerm"] ?? null), "html", null, true);
        yield "\"
                        placeholder=\"Buscar por nombre o email...\"
                        class=\"w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500\"
                    />
                </div>
            </div>
            <div class=\"flex gap-3\">
                <select name=\"status\" class=\"px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500\">
                    <option value=\"all\" ";
        // line 44
        yield (((($context["filterStatus"] ?? null) == "all")) ? ("selected") : (""));
        yield ">Todos los estados</option>
                    <option value=\"active\" ";
        // line 45
        yield (((($context["filterStatus"] ?? null) == "active")) ? ("selected") : (""));
        yield ">Activos</option>
                    <option value=\"inactive\" ";
        // line 46
        yield (((($context["filterStatus"] ?? null) == "inactive")) ? ("selected") : (""));
        yield ">Inactivos</option>
                </select>
                <button type=\"submit\" class=\"flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors\">
                    <i data-lucide=\"filter\" class=\"w-4 h-4\"></i>
                    Filtrar
                </button>
            </div>
        </form>
    </div>

    <!-- Stats -->
    <div class=\"grid grid-cols-1 md:grid-cols-4 gap-4\">
        <div class=\"bg-white rounded-lg p-4 shadow-sm border border-gray-200\">
            <p class=\"text-sm text-gray-600\">Total Voluntarios</p>
            <p class=\"text-2xl font-bold text-gray-900\">";
        // line 60
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["stats"] ?? null), "total", [], "any", false, false, false, 60), "html", null, true);
        yield "</p>
        </div>
        <div class=\"bg-white rounded-lg p-4 shadow-sm border border-gray-200\">
            <p class=\"text-sm text-gray-600\">Activos</p>
            <p class=\"text-2xl font-bold text-green-600\">";
        // line 64
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["stats"] ?? null), "Activo", [], "any", false, false, false, 64), "html", null, true);
        yield "</p>
        </div>
        <div class=\"bg-white rounded-lg p-4 shadow-sm border border-gray-200\">
            <p class=\"text-sm text-gray-600\">Coordinadores</p>
            <p class=\"text-2xl font-bold text-orange-600\">";
        // line 68
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["stats"] ?? null), "Suspensión", [], "any", false, false, false, 68), "html", null, true);
        yield "</p>
        </div>
        <div class=\"bg-white rounded-lg p-4 shadow-sm border border-gray-200\">
            <p class=\"text-sm text-gray-600\">Especialistas</p>
            <p class=\"text-2xl font-bold text-red-600\">";
        // line 72
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["stats"] ?? null), "Baja", [], "any", false, false, false, 72), "html", null, true);
        yield "</p>
        </div>
    </div>

    <!-- Table -->
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
        // line 91
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(($context["volunteers"] ?? null));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["_key"] => $context["volunteer"]) {
            // line 92
            yield "                        <tr class=\"hover:bg-gray-50\">
                            <td class=\"px-6 py-4\">
                                <div class=\"flex items-center gap-3\">
                                    <div class=\"w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center\">
                                        <span class=\"text-blue-600 font-medium\">
                                            ";
            // line 97
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::upper($this->env->getCharset(), Twig\Extension\CoreExtension::slice($this->env->getCharset(), Twig\Extension\CoreExtension::join(Twig\Extension\CoreExtension::map($this->env, Twig\Extension\CoreExtension::split($this->env->getCharset(), CoreExtension::getAttribute($this->env, $this->source, $context["volunteer"], "name", [], "any", false, false, false, 97), " "), function ($__name__) use ($context, $macros) { $context["name"] = $__name__; return Twig\Extension\CoreExtension::first($this->env->getCharset(), ($context["name"] ?? null)); }), ""), 0, 2)), "html", null, true);
            yield "
                                        </span>
                                    </div>
                                    <div>
                                        <p class=\"font-medium text-gray-900\"> ";
            // line 101
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["volunteer"], "name", [], "any", false, false, false, 101), "html", null, true);
            yield "</p>
                                        <p class=\"text-sm text-gray-500\">ID: ";
            // line 102
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["volunteer"], "id", [], "any", false, false, false, 102), "html", null, true);
            yield "</p>
                                    </div>
                                </div>
                            </td>
                            <td class=\"px-6 py-4\">
                                <div>
                                    <p class=\"text-sm text-gray-900\">";
            // line 108
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["volunteer"], "phone", [], "any", false, false, false, 108), "html", null, true);
            yield "</p>
                                    <p class=\"text-sm text-gray-500\">";
            // line 109
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["volunteer"], "user", [], "any", false, false, false, 109), "email", [], "any", false, false, false, 109), "html", null, true);
            yield "</p>
                                </div>
                            </td>
                            <td class=\"px-6 py-4\">
                                <p class=\"font-medium text-gray-900\">
                                    ";
            // line 114
            if ((($tmp =  !Twig\Extension\CoreExtension::testEmpty(CoreExtension::getAttribute($this->env, $this->source, $context["volunteer"], "drivingLicenses", [], "any", false, false, false, 114))) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                // line 115
                yield "                                        ";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::join(CoreExtension::getAttribute($this->env, $this->source, $context["volunteer"], "drivingLicenses", [], "any", false, false, false, 115), ", "), "html", null, true);
                yield "
                                    ";
            } else {
                // line 117
                yield "                                         ";
                // line 118
                yield "                                    ";
            }
            // line 119
            yield "                                </p>
                            </td>
                            <td class=\"px-6 py-4 text-sm text-gray-900\">
                                <p class=\"font-medium text-gray-900\">
                                    ";
            // line 123
            if ((($tmp =  !Twig\Extension\CoreExtension::testEmpty(CoreExtension::getAttribute($this->env, $this->source, $context["volunteer"], "specificQualifications", [], "any", false, false, false, 123))) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                // line 124
                yield "                                        ";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::join(CoreExtension::getAttribute($this->env, $this->source, $context["volunteer"], "specificQualifications", [], "any", false, false, false, 124), ", "), "html", null, true);
                yield "
                                    ";
            } else {
                // line 126
                yield "                                         ";
                // line 127
                yield "                                    ";
            }
            // line 128
            yield "                                </p>
                            </td>
                            <td class=\"px-6 py-4\">
                                 <p class=\"font-medium text-gray-900\">
                                    ";
            // line 132
            if ((($tmp =  !Twig\Extension\CoreExtension::testEmpty(CoreExtension::getAttribute($this->env, $this->source, $context["volunteer"], "navigationLicenses", [], "any", false, false, false, 132))) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                // line 133
                yield "                                        ";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::join(CoreExtension::getAttribute($this->env, $this->source, $context["volunteer"], "navigationLicenses", [], "any", false, false, false, 133), ", "), "html", null, true);
                yield "
                                    ";
            } else {
                // line 135
                yield "                                         ";
                // line 136
                yield "                                    ";
            }
            // line 137
            yield "                                </p>
                            </td>
                            <td class=\"px-6 py-4\">
                                <div class=\"flex items-center gap-2\">
                                    <button class=\"p-1 text-blue-600 hover:bg-blue-50 rounded\">
                                        <i data-lucide=\"eye\" class=\"w-4 h-4\"></i>
                                    </button>
                                    <a href=\"";
            // line 144
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Symfony\Bridge\Twig\Extension\RoutingExtension']->getPath("app_volunteer_edit", ["id" => CoreExtension::getAttribute($this->env, $this->source, $context["volunteer"], "id", [], "any", false, false, false, 144)]), "html", null, true);
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
        // line 154
        if (!$context['_iterated']) {
            // line 155
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
        // line 161
        yield "                </tbody>
            </table>
        </div>
    </div>
</div>
";
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
        return array (  319 => 161,  308 => 155,  306 => 154,  291 => 144,  282 => 137,  279 => 136,  277 => 135,  271 => 133,  269 => 132,  263 => 128,  260 => 127,  258 => 126,  252 => 124,  250 => 123,  244 => 119,  241 => 118,  239 => 117,  233 => 115,  231 => 114,  223 => 109,  219 => 108,  210 => 102,  206 => 101,  199 => 97,  192 => 92,  187 => 91,  165 => 72,  158 => 68,  151 => 64,  144 => 60,  127 => 46,  123 => 45,  119 => 44,  108 => 36,  88 => 19,  80 => 14,  70 => 6,  63 => 5,  52 => 3,  41 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "volunteer/list.html.twig", "C:\\xampp\\htdocs\\gesion_volunratios\\templates\\volunteer\\list.html.twig");
    }
}
