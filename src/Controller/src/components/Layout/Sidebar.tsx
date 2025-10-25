import React, { useState } from 'react';
import { 
  Home, Users, Settings, FileText, Phone, BarChart3, 
  ChevronDown, ChevronRight, Menu, X, Car, Package,
  Droplets, CreditCard, CheckSquare, Building,
  UserCog, Wrench, Shield, LogOut
} from 'lucide-react';
import { useAuth } from '../../contexts/AuthContext';

interface MenuItem {
  id: string;
  label: string;
  icon: React.ReactNode;
  children?: MenuItem[];
  onClick?: () => void;
}

interface SidebarProps {
  activeSection: string;
  onSectionChange: (section: string) => void;
}

const Sidebar: React.FC<SidebarProps> = ({ activeSection, onSectionChange }) => {
  const [isCollapsed, setIsCollapsed] = useState(false);
  const [expandedMenus, setExpandedMenus] = useState<Set<string>>(new Set(['gestion']));
  const { logout, user } = useAuth();

  const toggleMenu = (menuId: string) => {
    const newExpanded = new Set(expandedMenus);
    if (newExpanded.has(menuId)) {
      newExpanded.delete(menuId);
    } else {
      newExpanded.add(menuId);
    }
    setExpandedMenus(newExpanded);
  };

  const menuItems: MenuItem[] = [
    {
      id: 'inicio',
      label: 'Inicio',
      icon: <Home className="w-5 h-5" />,
      onClick: () => onSectionChange('inicio')
    },
    {
      id: 'gestion',
      label: 'Gestión',
      icon: <Users className="w-5 h-5" />,
      children: [
        {
          id: 'personal',
          label: 'Personal',
          icon: <Users className="w-4 h-4" />,
          children: [
            {
              id: 'personal-listado',
              label: 'Listado',
              icon: <FileText className="w-4 h-4" />,
              onClick: () => onSectionChange('personal-listado')
            },
            {
              id: 'personal-informes',
              label: 'Informes',
              icon: <BarChart3 className="w-4 h-4" />,
              onClick: () => onSectionChange('personal-informes')
            }
          ]
        },
        {
          id: 'servicios',
          label: 'Servicios',
          icon: <Building className="w-4 h-4" />,
          children: [
            {
              id: 'servicios-listado',
              label: 'Listado',
              icon: <FileText className="w-4 h-4" />,
              onClick: () => onSectionChange('servicios-listado')
            },
            {
              id: 'servicios-informes',
              label: 'Informes',
              icon: <BarChart3 className="w-4 h-4" />,
              onClick: () => onSectionChange('servicios-informes')
            },
            {
              id: 'servicios-cuadrantes',
              label: 'Cuadrantes',
              icon: <CheckSquare className="w-4 h-4" />,
              onClick: () => onSectionChange('servicios-cuadrantes')
            }
          ]
        },
        {
          id: 'comunicados',
          label: 'Comunicados y Alertas',
          icon: <Phone className="w-4 h-4" />,
          onClick: () => onSectionChange('comunicados')
        },
        {
          id: 'recursos',
          label: 'Recursos',
          icon: <Package className="w-4 h-4" />,
          children: [
            {
              id: 'vehiculos',
              label: 'Vehículos',
              icon: <Car className="w-4 h-4" />,
              onClick: () => onSectionChange('vehiculos')
            },
            {
              id: 'inventario',
              label: 'Inventario',
              icon: <Package className="w-4 h-4" />,
              children: [
                {
                  id: 'articulos',
                  label: 'Artículos',
                  icon: <Package className="w-3 h-3" />,
                  onClick: () => onSectionChange('articulos')
                },
                {
                  id: 'inventario-listado',
                  label: 'Listado',
                  icon: <FileText className="w-3 h-3" />,
                  onClick: () => onSectionChange('inventario-listado')
                },
                {
                  id: 'proveedores',
                  label: 'Proveedores',
                  icon: <Building className="w-3 h-3" />,
                  onClick: () => onSectionChange('proveedores')
                },
                {
                  id: 'entradas-salidas',
                  label: 'Entradas-Salidas',
                  icon: <FileText className="w-3 h-3" />,
                  onClick: () => onSectionChange('entradas-salidas')
                }
              ]
            },
            {
              id: 'arce',
              label: 'ARCE',
              icon: <Shield className="w-4 h-4" />,
              onClick: () => onSectionChange('arce')
            }
          ]
        },
        {
          id: 'hidrantes',
          label: 'Hidrantes',
          icon: <Droplets className="w-4 h-4" />,
          onClick: () => onSectionChange('hidrantes')
        },
        {
          id: 'filiaciones',
          label: 'Filiaciones',
          icon: <Users className="w-4 h-4" />,
          onClick: () => onSectionChange('filiaciones')
        },
        {
          id: 'tesoreria',
          label: 'Tesorería',
          icon: <CreditCard className="w-4 h-4" />,
          onClick: () => onSectionChange('tesoreria')
        },
        {
          id: 'tareas',
          label: 'Tareas',
          icon: <CheckSquare className="w-4 h-4" />,
          onClick: () => onSectionChange('tareas')
        }
      ]
    },
    {
      id: 'gesdoc',
      label: 'GESDOC',
      icon: <FileText className="w-5 h-5" />,
      onClick: () => onSectionChange('gesdoc')
    },
    {
      id: 'central',
      label: 'CENTRAL',
      icon: <Phone className="w-5 h-5" />,
      onClick: () => onSectionChange('central')
    },
    {
      id: 'estadisticas',
      label: 'ESTADÍSTICAS',
      icon: <BarChart3 className="w-5 h-5" />,
      onClick: () => onSectionChange('estadisticas')
    },
    {
      id: 'configuracion',
      label: 'CONFIGURACIÓN',
      icon: <Settings className="w-5 h-5" />,
      children: [
        {
          id: 'sistema',
          label: 'Sistema',
          icon: <Settings className="w-4 h-4" />,
          onClick: () => onSectionChange('sistema')
        },
        {
          id: 'utilidades',
          label: 'Utilidades',
          icon: <Wrench className="w-4 h-4" />,
          onClick: () => onSectionChange('utilidades')
        },
        {
          id: 'administradores',
          label: 'Administradores',
          icon: <UserCog className="w-4 h-4" />,
          onClick: () => onSectionChange('administradores')
        }
      ]
    }
  ];

  const renderMenuItem = (item: MenuItem, level: number = 0) => {
    const hasChildren = item.children && item.children.length > 0;
    const isExpanded = expandedMenus.has(item.id);
    const isActive = activeSection === item.id;

    return (
      <div key={item.id} className="mb-1">
        <div
          onClick={() => {
            if (hasChildren) {
              toggleMenu(item.id);
            } else if (item.onClick) {
              item.onClick();
            }
          }}
          className={`
            flex items-center gap-3 px-3 py-2 rounded-lg cursor-pointer transition-all duration-200
            ${isActive ? 'bg-blue-100 text-blue-700 font-medium' : 'text-gray-700 hover:bg-gray-100'}
            ${level > 0 ? 'ml-' + (level * 4) : ''}
          `}
        >
          {item.icon}
          {!isCollapsed && (
            <>
              <span className="flex-1 text-sm">{item.label}</span>
              {hasChildren && (
                isExpanded ? 
                <ChevronDown className="w-4 h-4" /> : 
                <ChevronRight className="w-4 h-4" />
              )}
            </>
          )}
        </div>
        
        {hasChildren && isExpanded && !isCollapsed && (
          <div className="ml-4 space-y-1">
            {item.children!.map(child => renderMenuItem(child, level + 1))}
          </div>
        )}
      </div>
    );
  };

  return (
    <div className={`bg-white shadow-lg transition-all duration-300 ${isCollapsed ? 'w-16' : 'w-80'} flex flex-col h-full`}>
      {/* Header */}
      <div className="p-4 border-b border-gray-200">
        <div className="flex items-center justify-between">
          {!isCollapsed && (
            <div className="flex items-center gap-3">
              <div className="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                <Users className="w-5 h-5 text-white" />
              </div>
              <div>
                <h2 className="font-semibold text-gray-900">SGV</h2>
                <p className="text-xs text-gray-500">Sistema Gestión Voluntarios</p>
              </div>
            </div>
          )}
          <button
            onClick={() => setIsCollapsed(!isCollapsed)}
            className="p-1 rounded-lg hover:bg-gray-100 transition-colors"
          >
            {isCollapsed ? <Menu className="w-5 h-5" /> : <X className="w-5 h-5" />}
          </button>
        </div>
      </div>

      {/* Navigation */}
      <div className="flex-1 p-4 overflow-y-auto">
        <nav className="space-y-2">
          {menuItems.map(item => renderMenuItem(item))}
        </nav>
      </div>

      {/* User section */}
      <div className="p-4 border-t border-gray-200">
        {!isCollapsed && (
          <div className="flex items-center gap-3 mb-3">
            <div className="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
              <Users className="w-4 h-4 text-gray-600" />
            </div>
            <div className="flex-1">
              <p className="font-medium text-sm text-gray-900">{user?.name}</p>
              <p className="text-xs text-gray-500">{user?.email}</p>
            </div>
          </div>
        )}
        <button
          onClick={logout}
          className="w-full flex items-center gap-3 px-3 py-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
        >
          <LogOut className="w-5 h-5" />
          {!isCollapsed && <span className="text-sm">Cerrar Sesión</span>}
        </button>
      </div>
    </div>
  );
};

export default Sidebar;