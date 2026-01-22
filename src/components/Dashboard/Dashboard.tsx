import React from 'react';
import { Users, Car, Package, Droplets, TrendingUp, Calendar, AlertTriangle, CheckCircle } from 'lucide-react';

const Dashboard: React.FC = () => {
  const stats = [
    {
      title: 'Voluntarrios Activos',
      value: '248',
      change: '+12',
      changeType: 'positive',
      icon: <Users className="w-6 h-6 text-blue-600" />
    },
    {
      title: 'Servicios del Mes',
      value: '89',
      change: '+5',
      changeType: 'positive',
      icon: <Calendar className="w-6 h-6 text-green-600" />
    },
    {
      title: 'Vehículos Disponibles',
      value: '15',
      change: '-2',
      changeType: 'negative',
      icon: <Car className="w-6 h-6 text-purple-600" />
    },
    {
      title: 'Alertas Pendientes',
      value: '3',
      change: '0',
      changeType: 'neutral',
      icon: <AlertTriangle className="w-6 h-6 text-orange-600" />
    }
  ];

  const recentActivities = [
    {
      id: 1,
      action: 'Nuevo voluntario registrado',
      user: 'María García',
      time: 'Hace 2 horas',
      type: 'success'
    },
    {
      id: 2,
      action: 'Servicio completado',
      user: 'Equipo Alpha',
      time: 'Hace 4 horas',
      type: 'info'
    },
    {
      id: 3,
      action: 'Mantenimiento vehículo',
      user: 'Taller Municipal',
      time: 'Hace 1 día',
      type: 'warning'
    },
    {
      id: 4,
      action: 'Informe mensual generado',
      user: 'Sistema',
      time: 'Hace 2 días',
      type: 'info'
    }
  ];

  const upcomingEvents = [
    {
      id: 1,
      title: 'Formación en Primeros Auxilios',
      date: '15 Mar 2024',
      time: '10:00',
      participants: 25
    },
    {
      id: 2,
      title: 'Simulacro de Emergencia',
      date: '18 Mar 2024',
      time: '09:00',
      participants: 40
    },
    {
      id: 3,
      title: 'Reunión Coordinadores',
      date: '22 Mar 2024',
      time: '16:00',
      participants: 8
    }
  ];

  return (
    <div className="p-6 space-y-6">
      {/* Welcome section */}
      <div className="bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl p-6 text-white">
        <h2 className="text-2xl font-bold mb-2">¡Bienvenido al Sistema de Gestión!</h2>
        <p className="text-blue-100">
          Gestiona voluntarios, recursos y servicios de manera eficiente desde un solo lugar.
        </p>
      </div>

      {/* Stats cards */}
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        {stats.map((stat, index) => (
          <div key={index} className="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
            <div className="flex items-center justify-between">
              <div>
                <p className="text-sm font-medium text-gray-600">{stat.title}</p>
                <p className="text-3xl font-bold text-gray-900 mt-2">{stat.value}</p>
                <div className="flex items-center mt-2">
                  <span className={`text-sm font-medium ${
                    stat.changeType === 'positive' ? 'text-green-600' : 
                    stat.changeType === 'negative' ? 'text-red-600' : 'text-gray-600'
                  }`}>
                    {stat.change !== '0' && (stat.changeType === 'positive' ? '+' : '')}{stat.change}
                  </span>
                  <span className="text-sm text-gray-500 ml-1">este mes</span>
                </div>
              </div>
              <div className="p-3 bg-gray-50 rounded-lg">
                {stat.icon}
              </div>
            </div>
          </div>
        ))}
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {/* Recent activities */}
        <div className="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
          <h3 className="text-lg font-semibold text-gray-900 mb-4">Actividad Reciente</h3>
          <div className="space-y-4">
            {recentActivities.map((activity) => (
              <div key={activity.id} className="flex items-start gap-3">
                <div className={`w-2 h-2 rounded-full mt-2 ${
                  activity.type === 'success' ? 'bg-green-500' :
                  activity.type === 'warning' ? 'bg-orange-500' : 'bg-blue-500'
                }`}></div>
                <div className="flex-1">
                  <p className="text-sm font-medium text-gray-900">{activity.action}</p>
                  <p className="text-sm text-gray-600">{activity.user}</p>
                  <p className="text-xs text-gray-500 mt-1">{activity.time}</p>
                </div>
              </div>
            ))}
          </div>
        </div>

        {/* Upcoming events */}
        <div className="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
          <h3 className="text-lg font-semibold text-gray-900 mb-4">Próximos Eventos</h3>
          <div className="space-y-4">
            {upcomingEvents.map((event) => (
              <div key={event.id} className="border border-gray-200 rounded-lg p-4">
                <h4 className="font-medium text-gray-900">{event.title}</h4>
                <div className="flex items-center gap-4 mt-2 text-sm text-gray-600">
                  <span>📅 {event.date}</span>
                  <span>🕐 {event.time}</span>
                  <span>👥 {event.participants} participantes</span>
                </div>
              </div>
            ))}
          </div>
        </div>
      </div>

      {/* Quick actions */}
      <div className="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
        <h3 className="text-lg font-semibold text-gray-900 mb-4">Acciones Rápidas</h3>
        <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
          <button className="flex items-center gap-3 p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
            <Users className="w-5 h-5 text-blue-600" />
            <span className="font-medium">Añadir Voluntario</span>
          </button>
          <button className="flex items-center gap-3 p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
            <Calendar className="w-5 h-5 text-green-600" />
            <span className="font-medium">Programar Servicio</span>
          </button>
          <button className="flex items-center gap-3 p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
            <TrendingUp className="w-5 h-5 text-purple-600" />
            <span className="font-medium">Ver Estadísticas</span>
          </button>
        </div>
      </div>
    </div>
  );
};

export default Dashboard;