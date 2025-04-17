
import React, { useEffect, useState } from "react";
import { Card, CardContent } from "@/components/ui/card";
import { Table, TableHead, TableRow, TableHeader, TableCell, TableBody } from "@/components/ui/table";
import { Progress } from "@/components/ui/progress";
import { BarChart, Bar, XAxis, YAxis, Tooltip, Legend, ResponsiveContainer } from "recharts";
import React from 'react';
import ReactDOM from 'react-dom';
import Dashboard from './components/Dashboard';

if (document.getElementById('safety-dashboard')) {
    ReactDOM.render(<Dashboard />, document.getElementById('safety-dashboard'));
}

const SafetyDashboard = () => {
  const [data, setData] = useState([]);
  const [workforce, setWorkforce] = useState({ shift1: 0, shift2: 0, shift3: 0, total: 0 });
  const [summary, setSummary] = useState({ lta: 0, wlta: 0, totalManHours: 0, lastLtaDate: "-" });

  useEffect(() => {
    fetch("/api/incidents")
      .then(res => res.json())
      .then(result => {
        setData(result.records);
        setWorkforce(result.workforce);
        setSummary(result.summary);
      });
  }, []);

  return (
    <div className="p-6 grid gap-6">
      <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
        <Card className="text-center">
          <CardContent>
            <p className="font-semibold">TARGET TOTAL MAN HOURS PER YEAR</p>
            <p className="text-3xl">1.000.000</p>
          </CardContent>
        </Card>

        <Card className="text-center">
          <CardContent>
            <p className="font-semibold">NUMBERS OF WORK FORCE</p>
            <div className="space-y-1">
              <p>Shift I: {workforce.shift1}</p>
              <p>Shift II: {workforce.shift2}</p>
              <p>Shift III: {workforce.shift3}</p>
              <p className="font-bold">Total: {workforce.total}</p>
            </div>
          </CardContent>
        </Card>

        <Card className="text-center">
          <CardContent>
            <p className="font-semibold">Jumlah Kecelakaan Disertai Hari Hilang (LTA)</p>
            <p className="text-3xl">{summary.lta}</p>
          </CardContent>
        </Card>

        <Card className="text-center">
          <CardContent>
            <p className="font-semibold">Jumlah Kecelakaan Tanpa Hari Hilang (WLTA)</p>
            <p className="text-3xl">{summary.wlta}</p>
          </CardContent>
        </Card>
      </div>

      <Card>
        <CardContent>
          <h2 className="text-xl font-bold mb-4">Incident List</h2>
          <Table>
            <TableHeader>
              <TableRow>
                <TableHead>NO</TableHead>
                <TableHead>INCIDENT DATE</TableHead>
                <TableHead>INCIDENT CLASSIFICATION</TableHead>
                <TableHead>TYPE OF INCIDENT</TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              {data.map((item, index) => (
                <TableRow key={item.id}>
                  <TableCell>{index + 1}</TableCell>
                  <TableCell>{item.shift_date}</TableCell>
                  <TableCell>{item.klasifikasi_kejadiannya}</TableCell>
                  <TableCell>{item.type_of_incident}</TableCell>
                </TableRow>
              ))}
            </TableBody>
          </Table>
        </CardContent>
      </Card>

      <div className="grid md:grid-cols-2 gap-4">
        <Card>
          <CardContent>
            <h2 className="text-lg font-bold mb-4">Case Per Company</h2>
            <ResponsiveContainer width="100%" height={250}>
              <BarChart data={summary.casePerCompany}>
                <XAxis dataKey="company" />
                <YAxis />
                <Tooltip />
                <Legend />
                <Bar dataKey="Near Miss" fill="#4f46e5" />
                <Bar dataKey="LTA" fill="#e11d48" />
                <Bar dataKey="WLTA" fill="#0ea5e9" />
              </BarChart>
            </ResponsiveContainer>
          </CardContent>
        </Card>

        <Card>
          <CardContent>
            <h2 className="text-lg font-bold mb-4">Case Per Department</h2>
            <ResponsiveContainer width="100%" height={250}>
              <BarChart data={summary.casePerDepartment}>
                <XAxis dataKey="department" />
                <YAxis />
                <Tooltip />
                <Legend />
                <Bar dataKey="Near Miss" fill="#4f46e5" />
                <Bar dataKey="LTA" fill="#e11d48" />
                <Bar dataKey="WLTA" fill="#0ea5e9" />
              </BarChart>
            </ResponsiveContainer>
          </CardContent>
        </Card>
      </div>

    </div>
  );
};

export default SafetyDashboard;
