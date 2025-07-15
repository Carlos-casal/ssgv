import React, { useState } from 'react';
import { AuthProvider, useAuth } from './contexts/AuthContext';
import Login from './components/Login';
import Sidebar from './components/Layout/Sidebar';
import Header from './components/Layout/Header';
import Dashboard from './components/Dashboard/Dashboard';
import PersonalListado from './components/Personal/PersonalListado';
import ComingSoon from './components/Common/ComingSoon';

const AppContent: React.FC = () => {
  const { isAuthenticated } = useAuth();
  const [activeSection, setActiveSection] = useState('inicio');

  if (!isAuthenticated) {
    return <Login />;
  }

  const getSectionTitle = (section: string): string => {
    const titles: { [key: string]: string } = {
      'inicio': 'Dashboard',
      'personal-listado': 'Listado de Personal',
      'personal-informes': 'Informes de Personal',
      'servicios-listado': 'Listado de Servicios',
      'servicios-informes': 'Informes de Servicios',
      'servicios-cuadrantes': 'Cuadrantes de Servicios',
      'comunicados': 'Comunicados y Alertas',
      'vehiculos': 'Gestión de Vehículos',
      'articulos': 'Artículos de Inventario',
      'inventario-listado': 'Listado de Inventario',
      'proveedores': 'Gestión de Proveedores',
      'entradas-salidas': 'Entradas y Salidas',
      'arce': 'Sistema ARCE',
      'hidrantes': 'Gestión de Hidrantes',
      'filiaciones': 'Gestión de Filiaciones',
      'tesoreria': 'Tesorería',
      'tareas': 'Gestión de Tareas',
      'gesdoc': 'GESDOC - Gestión Documental',
      'central': 'Central de Comunicaciones',
      'estadisticas': 'Estadísticas y Reportes',
      'sistema': 'Configuración del Sistema',
      'utilidades': 'Utilidades del Sistema',
      'administradores': 'Gestión de Administradores'
    };
    return titles[section] || 'Sección';
  };

  const renderContent = () => {
    switch (activeSection) {
      case 'inicio':
        return <Dashboard />;
      case 'personal-listado':
        return <PersonalListado />;
      default:
        return (
          <ComingSoon 
            title={getSectionTitle(activeSection)}
            description="Esta funcionalidad será implementada próximamente con todas las herramientas necesarias para una gestión eficiente."
          />
        );
    }
  };

  return (
    <div className="flex h-screen bg-gray-50">
      <Sidebar activeSection={activeSection} onSectionChange={setActiveSection} />
      <div className="flex-1 flex flex-col overflow-hidden">
        <Header title={getSectionTitle(activeSection)} />
        <main className="flex-1 overflow-y-auto">
          {renderContent()}
        </main>
      </div>
    </div>
  );
};

function App() {
  return (
    <AuthProvider>
      <AppContent />
    </AuthProvider>
  );
}

export default App;