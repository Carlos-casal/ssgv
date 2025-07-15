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
        ";
        // line 25
        yield "        <div class=\"mb-4 border-b border-gray-200 dark:border-gray-700\">
            <ul class=\"flex flex-wrap -mb-px text-sm font-medium text-center\" id=\"myTab\" data-tabs-toggle=\"#myTabContent\" role=\"tablist\">
                <li class=\"me-2\" role=\"presentation\">
                    <button class=\"inline-block p-4 border-b-2 rounded-t-lg\" id=\"basic-data-tab\" data-tabs-target=\"#basic-data\" type=\"button\" role=\"tab\" aria-controls=\"basic-data\" aria-selected=\"true\">Datos Básicos</button>
                </li>
                <li class=\"me-2\" role=\"presentation\">
                    <button class=\"inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300\" id=\"attendance-confirmation-tab\" data-tabs-target=\"#attendance-confirmation\" type=\"button\" role=\"tab\" aria-controls=\"attendance-confirmation\" aria-selected=\"false\">Confirmación de Asistencia</button>
                </li>
                ";
        // line 34
        yield "            </ul>
        </div>

        ";
        // line 38
        yield "        <div id=\"myTabContent\">
            ";
        // line 40
        yield "            <div class=\"p-4 rounded-lg bg-gray-50 dark:bg-gray-800\" id=\"basic-data\" role=\"tabpanel\" aria-labelledby=\"basic-data-tab\">
                <h2 class=\"text-xl font-semibold mb-4 text-gray-900\">Formulario de Datos Básicos</h2>
                ";
        // line 43
        yield "                ";
        yield         $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->renderBlock((isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 43, $this->source); })()), 'form_start', ["attr" => ["class" => "bg-white p-6 rounded-lg shadow-md"], "enctype" => "multipart/form-data"]);
        yield "

                ";
        // line 46
        yield "                <div class=\"grid grid-cols-1 md:grid-cols-3 gap-6\">
                    ";
        // line 48
        yield "                    <div class=\"col-span-1\">
                        <h3 class=\"text-lg font-medium text-gray-900 mb-4 border-b pb-2\">Datos del Servicio</h3>
                        <div class=\"mb-4\">
                            ";
        // line 51
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 51, $this->source); })()), "title", [], "any", false, false, false, 51), 'row', ["label_attr" => ["class" => "block text-sm font-medium text-gray-700"]]);
        yield "
                        </div>
                        <div class=\"mb-4\">
                            ";
        // line 54
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 54, $this->source); })()), "numeration", [], "any", false, false, false, 54), 'row', ["label_attr" => ["class" => "block text-sm font-medium text-gray-700"]]);
        yield "
                        </div>
                        <div class=\"mb-4\">
                            ";
        // line 57
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 57, $this->source); })()), "startDate", [], "any", false, false, false, 57), 'row', ["label_attr" => ["class" => "block text-sm font-medium text-gray-700"]]);
        yield "
                        </div>
                        <div class=\"mb-4\">
                            ";
        // line 60
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 60, $this->source); })()), "endDate", [], "any", false, false, false, 60), 'row', ["label_attr" => ["class" => "block text-sm font-medium text-gray-700"]]);
        yield "
                        </div>
                        <div class=\"mb-4\">
                            ";
        // line 63
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 63, $this->source); })()), "maxAttendees", [], "any", false, false, false, 63), 'row', ["label_attr" => ["class" => "block text-sm font-medium text-gray-700"]]);
        yield "
                        </div>
                        <div class=\"mb-4\">
                            ";
        // line 66
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 66, $this->source); })()), "type", [], "any", false, false, false, 66), 'row', ["label_attr" => ["class" => "block text-sm font-medium text-gray-700"]]);
        yield "
                        </div>
                        <div class=\"mb-4\">
                            ";
        // line 69
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 69, $this->source); })()), "category", [], "any", false, false, false, 69), 'row', ["label_attr" => ["class" => "block text-sm font-medium text-gray-700"]]);
        yield "
                        </div>
                        <div class=\"mb-4\">
                            ";
        // line 72
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 72, $this->source); })()), "description", [], "any", false, false, false, 72), 'row', ["label_attr" => ["class" => "block text-sm font-medium text-gray-700"]]);
        yield "
                        </div>
                        <div class=\"mb-4\">
                            ";
        // line 75
        yield $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->searchAndRenderBlock(CoreExtension::getAttribute($this->env, $this->source, (isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 75, $this->source); })()), "recipients", [], "any", false, false, false, 75), 'row', ["label_attr" => ["class" => "block text-sm font-medium text-gray-700"]]);
        yield "
                        </div>
                    </div>
                    ";
        // line 79
        yield "                    <div class=\"col-span-1\">
                        ";
        // line 81
        yield "                    </div>
                    <div class=\"col-span-1\">
                        ";
        // line 84
        yield "                    </div>
                </div>

                <div class=\"mt-6 text-center\">
                    <button type=\"submit\" class=\"bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300 ease-in-out\">
                        Guardar Cambios
                    </button>
                </div>

                ";
        // line 93
        yield         $this->env->getRuntime('Symfony\Component\Form\FormRenderer')->renderBlock((isset($context["form"]) || array_key_exists("form", $context) ? $context["form"] : (function () { throw new RuntimeError('Variable "form" does not exist.', 93, $this->source); })()), 'form_end');
        yield "
            </div>

            ";
        // line 97
        yield "            ";
        // line 98
        yield "            <div class=\"hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800\" id=\"attendance-confirmation\" role=\"tabpanel\" aria-labelledby=\"attendance-confirmation-tab\">
                <h2 class=\"text-xl font-semibold mb-4 text-gray-900\">Confirmación de Asistencia a Servicios</h2>

                ";
        // line 101
        if ((($tmp =  !Twig\Extension\CoreExtension::testEmpty((isset($context["services_attendance"]) || array_key_exists("services_attendance", $context) ? $context["services_attendance"] : (function () { throw new RuntimeError('Variable "services_attendance" does not exist.', 101, $this->source); })()))) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 102
            yield "                    <div class=\"relative overflow-x-auto shadow-md sm:rounded-lg\">
                        <table class=\"w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400\">
                            <thead class=\"text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400\">
                                <tr>
                                    <th scope=\"col\" class=\"px-6 py-3\">Voluntario</th>
                                    <th scope=\"col\" class=\"px-6 py-3\">Estado de Asistencia</th>
                                    <th scope=\"col\" class=\"px-6 py-3\">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                ";
            // line 112
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable((isset($context["services_attendance"]) || array_key_exists("services_attendance", $context) ? $context["services_attendance"] : (function () { throw new RuntimeError('Variable "services_attendance" does not exist.', 112, $this->source); })()));
            foreach ($context['_seq'] as $context["_key"] => $context["volunteerService"]) {
                // line 113
                yield "                                    <tr class=\"bg-white border-b dark:bg-gray-800 dark:border-gray-700\">
                                        <td class=\"px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white\">
                                            ";
                // line 116
                yield "                                            ";
                // line 117
                yield "                                            ";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["volunteerService"], "volunteer", [], "any", false, false, false, 117), "name", [], "any", false, false, false, 117), "html", null, true);
                yield " ";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["volunteerService"], "volunteer", [], "any", false, false, false, 117), "lastName", [], "any", false, false, false, 117), "html", null, true);
                yield "
                                        </td>
                                        <td class=\"px-6 py-4\">
                                            ";
                // line 121
                yield "                                            ";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, $context["volunteerService"], "status", [], "any", false, false, false, 121), "html", null, true);
                yield "
                                        </td>
                                        <td class=\"px-6 py-4\">
                                            ";
                // line 125
                yield "                                            <a href=\"#\" class=\"font-medium text-blue-600 dark:text-blue-500 hover:underline\">Cambiar Estado</a>
                                        </td>
                                    </tr>
                                ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_key'], $context['volunteerService'], $context['_parent']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 129
            yield "                            </tbody>
                        </table>
                    </div>
                ";
        } else {
            // line 133
            yield "                    <p class=\"text-gray-600 dark:text-gray-300\">No hay asistencias registradas para este servicio aún.</p>
                ";
        }
        // line 135
        yield "            </div>
            ";
        // line 137
        yield "        </div>
    </div>

";
        
        $__internal_6f47bbe9983af81f1e7450e9a3e3768f->leave($__internal_6f47bbe9983af81f1e7450e9a3e3768f_prof);

        
        $__internal_5a27a8ba21ca79b61932376b2fa922d2->leave($__internal_5a27a8ba21ca79b61932376b2fa922d2_prof);

        yield from [];
    }

    // line 142
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

        // line 143
        yield "    ";
        yield from $this->yieldParentBlock("javascripts", $context, $blocks);
        yield " ";
        // line 144
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
        return array (  358 => 144,  354 => 143,  341 => 142,  327 => 137,  324 => 135,  320 => 133,  314 => 129,  305 => 125,  298 => 121,  289 => 117,  287 => 116,  283 => 113,  279 => 112,  267 => 102,  265 => 101,  260 => 98,  258 => 97,  252 => 93,  241 => 84,  237 => 81,  234 => 79,  228 => 75,  222 => 72,  216 => 69,  210 => 66,  204 => 63,  198 => 60,  192 => 57,  186 => 54,  180 => 51,  175 => 48,  172 => 46,  166 => 43,  162 => 40,  159 => 38,  154 => 34,  144 => 25,  141 => 23,  132 => 20,  129 => 19,  124 => 18,  115 => 15,  112 => 14,  107 => 13,  101 => 8,  88 => 7,  65 => 5,  42 => 3,);
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

        {# Contenedor principal para las pestañas de navegación #}
        <div class=\"mb-4 border-b border-gray-200 dark:border-gray-700\">
            <ul class=\"flex flex-wrap -mb-px text-sm font-medium text-center\" id=\"myTab\" data-tabs-toggle=\"#myTabContent\" role=\"tablist\">
                <li class=\"me-2\" role=\"presentation\">
                    <button class=\"inline-block p-4 border-b-2 rounded-t-lg\" id=\"basic-data-tab\" data-tabs-target=\"#basic-data\" type=\"button\" role=\"tab\" aria-controls=\"basic-data\" aria-selected=\"true\">Datos Básicos</button>
                </li>
                <li class=\"me-2\" role=\"presentation\">
                    <button class=\"inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300\" id=\"attendance-confirmation-tab\" data-tabs-target=\"#attendance-confirmation\" type=\"button\" role=\"tab\" aria-controls=\"attendance-confirmation\" aria-selected=\"false\">Confirmación de Asistencia</button>
                </li>
                {# Si tienes más pestañas, añádelas aquí con sus respectivos IDs y data-tabs-target #}
            </ul>
        </div>

        {# Contenido de las pestañas #}
        <div id=\"myTabContent\">
            {# Contenido de Datos Básicos #}
            <div class=\"p-4 rounded-lg bg-gray-50 dark:bg-gray-800\" id=\"basic-data\" role=\"tabpanel\" aria-labelledby=\"basic-data-tab\">
                <h2 class=\"text-xl font-semibold mb-4 text-gray-900\">Formulario de Datos Básicos</h2>
                {# Contenedor principal del formulario con estilos #}
                {{ form_start(form, {'attr': {'class': 'bg-white p-6 rounded-lg shadow-md'}, 'enctype': 'multipart/form-data'}) }}

                {# CONTENEDOR PRINCIPAL DEL FORMULARIO CON 3 COLUMNAS #}
                <div class=\"grid grid-cols-1 md:grid-cols-3 gap-6\">
                    {# Columna 1: Datos del Servicio #}
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
                    {# Aquí puedes añadir Columna 2 y Columna 3 si las tienes en tu diseño #}
                    <div class=\"col-span-1\">
                        {# Otros campos o información relacionados con el servicio #}
                    </div>
                    <div class=\"col-span-1\">
                        {# Más campos o información relacionados con el servicio #}
                    </div>
                </div>

                <div class=\"mt-6 text-center\">
                    <button type=\"submit\" class=\"bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300 ease-in-out\">
                        Guardar Cambios
                    </button>
                </div>

                {{ form_end(form) }}
            </div>

            {# Contenido de Confirmación de Asistencia #}
            {# Este div inicialmente tendrá la clase 'hidden' y el JS la quitará al activar la pestaña #}
            <div class=\"hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800\" id=\"attendance-confirmation\" role=\"tabpanel\" aria-labelledby=\"attendance-confirmation-tab\">
                <h2 class=\"text-xl font-semibold mb-4 text-gray-900\">Confirmación de Asistencia a Servicios</h2>

                {% if services_attendance is not empty %}
                    <div class=\"relative overflow-x-auto shadow-md sm:rounded-lg\">
                        <table class=\"w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400\">
                            <thead class=\"text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400\">
                                <tr>
                                    <th scope=\"col\" class=\"px-6 py-3\">Voluntario</th>
                                    <th scope=\"col\" class=\"px-6 py-3\">Estado de Asistencia</th>
                                    <th scope=\"col\" class=\"px-6 py-3\">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                {% for volunteerService in services_attendance %}
                                    <tr class=\"bg-white border-b dark:bg-gray-800 dark:border-gray-700\">
                                        <td class=\"px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white\">
                                            {# Asumiendo que VolunteerService tiene un método getVolunteer() que devuelve la entidad Volunteer #}
                                            {# Y que la entidad Volunteer tiene métodos getName() y getLastName() #}
                                            {{ volunteerService.volunteer.name }} {{ volunteerService.volunteer.lastName }}
                                        </td>
                                        <td class=\"px-6 py-4\">
                                            {# Asumiendo que VolunteerService tiene un método getStatus() #}
                                            {{ volunteerService.status }}
                                        </td>
                                        <td class=\"px-6 py-4\">
                                            {# Aquí puedes añadir botones o enlaces para cambiar el estado de asistencia #}
                                            <a href=\"#\" class=\"font-medium text-blue-600 dark:text-blue-500 hover:underline\">Cambiar Estado</a>
                                        </td>
                                    </tr>
                                {% endfor %}
                            </tbody>
                        </table>
                    </div>
                {% else %}
                    <p class=\"text-gray-600 dark:text-gray-300\">No hay asistencias registradas para este servicio aún.</p>
                {% endif %}
            </div>
            {# Aquí puedes añadir más divs para el contenido de otras pestañas #}
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
