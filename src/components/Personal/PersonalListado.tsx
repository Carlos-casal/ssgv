import React, { useState } from 'react';
import { Search, Plus, Download, Edit, Trash2, Eye, Filter } from 'lucide-react';

interface Volunteer {
  id: string;
  name: string;
  email: string;
  phone: string;
  role: string;
  status: 'active' | 'inactive';
  joinDate: string;
}

const PersonalListado: React.FC = () => {
  const [searchTerm, setSearchTerm] = useState('');
  const [filterStatus, setFilterStatus] = useState('all');
  const [showAddModal, setShowAddModal] = useState(false);

  const volunteers: Volunteer[] = [
    {
      id: '1',
      name: 'Ana Martínez López',
      email: 'ana.martinez@email.com',
      phone: '+34 600 123 456',
      role: 'Coordinador',
      status: 'active',
      joinDate: '2023-01-15'
    },
    {
      id: '2',
      name: 'Carlos García Ruiz',
      email: 'carlos.garcia@email.com',
      phone: '+34 600 234 567',
      role: 'Voluntario',
      status: 'active',
      joinDate: '2023-03-20'
    },
    {
      id: '3',
      name: 'María José Fernández',
      email: 'mj.fernandez@email.com',
      phone: '+34 600 345 678',
      role: 'Voluntario',
      status: 'inactive',
      joinDate: '2022-11-10'
    },
    {
      id: '4',
      name: 'Pedro Sánchez Díaz',
      email: 'pedro.sanchez@email.com',
      phone: '+34 600 456 789',
      role: 'Especialista',
      status: 'active',
      joinDate: '2023-02-05'
    }
  ];

  const filteredVolunteers = volunteers.filter(volunteer => {
    const matchesSearch = volunteer.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
                         volunteer.email.toLowerCase().includes(searchTerm.toLowerCase());
    const matchesStatus = filterStatus === 'all' || volunteer.status === filterStatus;
    return matchesSearch && matchesStatus;
  });

  const exportToCSV = () => {
    const headers = ['Nombre', 'Email', 'Teléfono', 'Rol', 'Estado', 'Fecha Ingreso'];
    const csvContent = [
      headers.join(','),
      ...filteredVolunteers.map(volunteer => [
        volunteer.name,
        volunteer.email,
        volunteer.phone,
        volunteer.role,
        volunteer.status === 'active' ? 'Activo' : 'Inactivo',
        volunteer.joinDate
      ].join(','))
    ].join('\n');

    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    link.setAttribute('href', url);
    link.setAttribute('download', 'voluntarios.csv');
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
  };

  return (
    <div className="p-6 space-y-6">
      {/* Header */}
      <div className="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
          <h2 className="text-2xl font-bold text-gray-900">Listado de Personal</h2>
          <p className="text-gray-600">Gestiona toda la información de voluntarios</p>
        </div>
        <div className="flex gap-3">
          <button
            onClick={exportToCSV}
            className="flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors"
          >
            <Download className="w-4 h-4" />
            Exportar CSV
          </button>
          <button
            onClick={() => setShowAddModal(true)}
            className="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
          >
            <Plus className="w-4 h-4" />
            Nuevo Voluntario
          </button>
        </div>
      </div>

      {/* Filters */}
      <div className="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
        <div className="flex flex-col lg:flex-row gap-4">
          <div className="flex-1">
            <div className="relative">
              <Search className="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" />
              <input
                type="text"
                placeholder="Buscar por nombre o email..."
                value={searchTerm}
                onChange={(e) => setSearchTerm(e.target.value)}
                className="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              />
            </div>
          </div>
          <div className="flex gap-3">
            <select
              value={filterStatus}
              onChange={(e) => setFilterStatus(e.target.value)}
              className="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            >
              <option value="all">Todos los estados</option>
              <option value="active">Activos</option>
              <option value="inactive">Inactivos</option>
            </select>
            <button className="flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
              <Filter className="w-4 h-4" />
              Más filtros
            </button>
          </div>
        </div>
      </div>

      {/* Stats */}
      <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div className="bg-white rounded-lg p-4 shadow-sm border border-gray-200">
          <p className="text-sm text-gray-600">Total Voluntarios</p>
          <p className="text-2xl font-bold text-gray-900">{volunteers.length}</p>
        </div>
        <div className="bg-white rounded-lg p-4 shadow-sm border border-gray-200">
          <p className="text-sm text-gray-600">Activos</p>
          <p className="text-2xl font-bold text-green-600">
            {volunteers.filter(v => v.status === 'active').length}
          </p>
        </div>
        <div className="bg-white rounded-lg p-4 shadow-sm border border-gray-200">
          <p className="text-sm text-gray-600">Coordinadores</p>
          <p className="text-2xl font-bold text-blue-600">
            {volunteers.filter(v => v.role === 'Coordinador').length}
          </p>
        </div>
        <div className="bg-white rounded-lg p-4 shadow-sm border border-gray-200">
          <p className="text-sm text-gray-600">Especialistas</p>
          <p className="text-2xl font-bold text-purple-600">
            {volunteers.filter(v => v.role === 'Especialista').length}
          </p>
        </div>
      </div>

      {/* Table */}
      <div className="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div className="overflow-x-auto">
          <table className="w-full">
            <thead className="bg-gray-50">
              <tr>
                <th className="text-left px-6 py-3 text-sm font-medium text-gray-900">Voluntario</th>
                <th className="text-left px-6 py-3 text-sm font-medium text-gray-900">Contacto</th>
                <th className="text-left px-6 py-3 text-sm font-medium text-gray-900">Rol</th>
                <th className="text-left px-6 py-3 text-sm font-medium text-gray-900">Estado</th>
                <th className="text-left px-6 py-3 text-sm font-medium text-gray-900">Fecha Ingreso</th>
                <th className="text-left px-6 py-3 text-sm font-medium text-gray-900">Acciones</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-gray-200">
              {filteredVolunteers.map((volunteer) => (
                <tr key={volunteer.id} className="hover:bg-gray-50">
                  <td className="px-6 py-4">
                    <div className="flex items-center gap-3">
                      <div className="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                        <span className="text-blue-600 font-medium">
                          {volunteer.name.split(' ').map(n => n[0]).join('').substring(0, 2)}
                        </span>
                      </div>
                      <div>
                        <p className="font-medium text-gray-900">{volunteer.name}</p>
                        <p className="text-sm text-gray-500">ID: {volunteer.id}</p>
                      </div>
                    </div>
                  </td>
                  <td className="px-6 py-4">
                    <div>
                      <p className="text-sm text-gray-900">{volunteer.email}</p>
                      <p className="text-sm text-gray-500">{volunteer.phone}</p>
                    </div>
                  </td>
                  <td className="px-6 py-4">
                    <span className={`inline-flex px-2 py-1 text-xs font-medium rounded-full ${
                      volunteer.role === 'Coordinador' ? 'bg-purple-100 text-purple-800' :
                      volunteer.role === 'Especialista' ? 'bg-blue-100 text-blue-800' :
                      'bg-gray-100 text-gray-800'
                    }`}>
                      {volunteer.role}
                    </span>
                  </td>
                  <td className="px-6 py-4">
                    <span className={`inline-flex px-2 py-1 text-xs font-medium rounded-full ${
                      volunteer.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                    }`}>
                      {volunteer.status === 'active' ? 'Activo' : 'Inactivo'}
                    </span>
                  </td>
                  <td className="px-6 py-4 text-sm text-gray-900">
                    {new Date(volunteer.joinDate).toLocaleDateString('es-ES')}
                  </td>
                  <td className="px-6 py-4">
                    <div className="flex items-center gap-2">
                      <button className="p-1 text-blue-600 hover:bg-blue-50 rounded">
                        <Eye className="w-4 h-4" />
                      </button>
                      <button className="p-1 text-green-600 hover:bg-green-50 rounded">
                        <Edit className="w-4 h-4" />
                      </button>
                      <button className="p-1 text-red-600 hover:bg-red-50 rounded">
                        <Trash2 className="w-4 h-4" />
                      </button>
                    </div>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>

      {/* Add Modal (simplified) */}
      {showAddModal && (
        <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
          <div className="bg-white rounded-xl p-6 w-full max-w-md">
            <h3 className="text-lg font-semibold mb-4">Nuevo Voluntario</h3>
            <div className="space-y-4">
              <input
                type="text"
                placeholder="Nombre completo"
                className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              />
              <input
                type="email"
                placeholder="Email"
                className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              />
              <input
                type="tel"
                placeholder="Teléfono"
                className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              />
              <select className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">Seleccionar rol</option>
                <option value="Voluntario">Voluntario</option>
                <option value="Coordinador">Coordinador</option>
                <option value="Especialista">Especialista</option>
              </select>
            </div>
            <div className="flex gap-3 mt-6">
              <button
                onClick={() => setShowAddModal(false)}
                className="flex-1 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
              >
                Cancelar
              </button>
              <button
                onClick={() => setShowAddModal(false)}
                className="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
              >
                Guardar
              </button>
            </div>
          </div>
        </div>
      )}
    </div>
  );
};

export default PersonalListado;