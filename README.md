# General Services

Aplicación web PHP (MVC) desplegada con Docker y Kubernetes (Helm).
PHP MVC web application deployed with Docker and Kubernetes (Helm).

---

## Español

### Requisitos previos

- [Docker](https://docs.docker.com/get-docker/)
- [kubectl](https://kubernetes.io/docs/tasks/tools/) configurado contra el cluster
- [Helm](https://helm.sh/docs/intro/install/) v3
- Namespace `general-services` creado en el cluster
- Acceso a Docker Hub con la cuenta `christopherguzman`

### Estructura del proyecto

```
.
├── deployment/                # Helm chart de la aplicación PHP
│   ├── Chart.yaml
│   ├── values.yaml
│   ├── 000-default.conf
│   └── templates/
│       ├── configmap.yaml
│       ├── deployment.yaml
│       ├── ingress.yaml
│       ├── pvc.yaml           # PersistentVolumeClaim para imágenes
│       └── service.yaml
├── deployment/my-sql/         # Helm chart de MySQL
│   ├── Chart.yaml
│   ├── values.yaml
│   └── templates/
├── Dockerfile
├── deploy.sh                  # Build + push + helm upgrade en un solo paso
├── backup-images.sh           # Copia imágenes del pod al directorio local
├── restore-images.sh          # Restaura imágenes locales al pod
├── setup-dev-env.sh           # Instalación interactiva de herramientas de desarrollo
└── ...
```

### Configuración

Los valores configurables se encuentran en `deployment/values.yaml`:

| Variable                  | Valor por defecto                              | Descripción                        |
|---------------------------|------------------------------------------------|------------------------------------|
| `replicaCount`            | `3`                                            | Réplicas de la app                 |
| `volume.name`             | `shared-volume`                                | Nombre del PVC                     |
| `volume.mountPath`        | `/var/www/public/image`                        | Ruta de montaje dentro del pod     |
| `volume.storageClassName` | `''` (clase por defecto del cluster)           | StorageClass del PVC               |
| `volume.accessMode`       | `ReadWriteMany`                                | Modo de acceso del PVC             |
| `volume.size`             | `8Gi`                                          | Tamaño del PVC                     |
| `DB_HOST`                 | `mysql.general-services.svc.cluster.local`     | Host de MySQL en el cluster        |
| `DB_NAME`                 | `gestionproyectos`                             | Nombre de la base de datos         |
| `DB_USER`                 | `root`                                         | Usuario de MySQL                   |
| `DB_PASSWORD`             | `general-services`                             | Contraseña de MySQL                |
| `ingress.host`            | `gs.iguzman.com.mx`                            | Dominio de la aplicación           |

### Configurar el entorno de desarrollo

Ejecuta el script interactivo para verificar e instalar las herramientas necesarias (git, Node.js, Docker, kubectl, Helm, Claude Code, etc.):

```bash
bash setup-dev-env.sh
```

### Despliegue de MySQL (primera vez)

Si MySQL aún no está instalado en el cluster:

```bash
helm -n general-services install mysql deployment/my-sql
```

### Despliegue de la aplicación

#### Comando rápido (build + push + helm upgrade)

```bash
bash deploy.sh
```

El script incrementa automáticamente el número de versión (`.version`), construye y publica la imagen, y actualiza el Helm release.

#### Pasos manuales

**1. Construir la imagen Docker**

```bash
docker build -t christopherguzman/general-services:<version> .
```

**2. Publicar la imagen en Docker Hub**

```bash
docker push christopherguzman/general-services:<version>
```

**3. Primera instalación con Helm**

```bash
helm -n general-services install general-services deployment \
  --set image.tag=<version>
```

**4. Actualización con Helm**

```bash
helm -n general-services upgrade general-services deployment \
  --set image.tag=<version>
```

### Volumen persistente (PVC)

Las imágenes subidas se almacenan en un `PersistentVolumeClaim` gestionado por Helm. El PVC se crea automáticamente al instalar el chart y se conserva entre despliegues gracias a la anotación `helm.sh/resource-policy: keep`.

Los pods arrancan correctamente en cuanto el directorio de montaje (`/var/www/public/image`) exista, lo cual ocurre de forma inmediata tras la provisión del PVC.

Para ajustar la capacidad o la clase de almacenamiento, edita `deployment/values.yaml`:

```yaml
volume:
  name: 'shared-volume'
  mountPath: /var/www/public/image
  storageClassName: ''     # dejar vacío para usar la clase por defecto
  accessMode: ReadWriteMany
  size: 8Gi
```

### Backup y restauración de imágenes

**Hacer backup** (copia del pod al directorio `backup-images/` local):

```bash
bash backup-images.sh
```

**Restaurar** (copia de `backup-images/` local al pod):

```bash
bash restore-images.sh
```

Ambos scripts localizan automáticamente un pod en ejecución en el namespace `general-services`.

### Verificar el despliegue

```bash
kubectl -n general-services get pods
kubectl -n general-services get pvc
kubectl -n general-services get ingress
```

La aplicación quedará disponible en: `https://gs.iguzman.com.mx`

---

## English

### Prerequisites

- [Docker](https://docs.docker.com/get-docker/)
- [kubectl](https://kubernetes.io/docs/tasks/tools/) configured against the cluster
- [Helm](https://helm.sh/docs/intro/install/) v3
- Namespace `general-services` created in the cluster
- Docker Hub access with the `christopherguzman` account

### Project structure

```
.
├── deployment/                # PHP application Helm chart
│   ├── Chart.yaml
│   ├── values.yaml
│   ├── 000-default.conf
│   └── templates/
│       ├── configmap.yaml
│       ├── deployment.yaml
│       ├── ingress.yaml
│       ├── pvc.yaml           # PersistentVolumeClaim for images
│       └── service.yaml
├── deployment/my-sql/         # MySQL Helm chart
│   ├── Chart.yaml
│   ├── values.yaml
│   └── templates/
├── Dockerfile
├── deploy.sh                  # Build + push + helm upgrade in one step
├── backup-images.sh           # Copy images from pod to local directory
├── restore-images.sh          # Restore local images to pod
├── setup-dev-env.sh           # Interactive dev tools installer
└── ...
```

### Configuration

Configurable values are found in `deployment/values.yaml`:

| Variable                  | Default value                                  | Description                        |
|---------------------------|------------------------------------------------|------------------------------------|
| `replicaCount`            | `3`                                            | App replicas                       |
| `volume.name`             | `shared-volume`                                | PVC name                           |
| `volume.mountPath`        | `/var/www/public/image`                        | Mount path inside the pod          |
| `volume.storageClassName` | `''` (cluster default class)                   | PVC StorageClass                   |
| `volume.accessMode`       | `ReadWriteMany`                                | PVC access mode                    |
| `volume.size`             | `8Gi`                                          | PVC size                           |
| `DB_HOST`                 | `mysql.general-services.svc.cluster.local`     | MySQL host in the cluster          |
| `DB_NAME`                 | `gestionproyectos`                             | Database name                      |
| `DB_USER`                 | `root`                                         | MySQL user                         |
| `DB_PASSWORD`             | `general-services`                             | MySQL password                     |
| `ingress.host`            | `gs.iguzman.com.mx`                            | Application domain                 |

### Set up the development environment

Run the interactive script to check and install required tools (git, Node.js, Docker, kubectl, Helm, Claude Code, etc.):

```bash
bash setup-dev-env.sh
```

### Deploy MySQL (first time)

If MySQL is not yet installed in the cluster:

```bash
helm -n general-services install mysql deployment/my-sql
```

### Deploy the application

#### Quick command (build + push + helm upgrade)

```bash
bash deploy.sh
```

The script automatically increments the version number (`.version`), builds and pushes the image, and upgrades the Helm release.

#### Manual steps

**1. Build the Docker image**

```bash
docker build -t christopherguzman/general-services:<version> .
```

**2. Push the image to Docker Hub**

```bash
docker push christopherguzman/general-services:<version>
```

**3. First install with Helm**

```bash
helm -n general-services install general-services deployment \
  --set image.tag=<version>
```

**4. Upgrade with Helm**

```bash
helm -n general-services upgrade general-services deployment \
  --set image.tag=<version>
```

### Persistent volume (PVC)

Uploaded images are stored in a `PersistentVolumeClaim` managed by Helm. The PVC is created automatically when the chart is installed and is preserved across deployments thanks to the `helm.sh/resource-policy: keep` annotation.

Pods start successfully as soon as the mount directory (`/var/www/public/image`) exists, which happens immediately after PVC provisioning.

To adjust capacity or storage class, edit `deployment/values.yaml`:

```yaml
volume:
  name: 'shared-volume'
  mountPath: /var/www/public/image
  storageClassName: ''     # leave empty to use the cluster default
  accessMode: ReadWriteMany
  size: 8Gi
```

### Image backup and restore

**Backup** (copy from pod to local `backup-images/` directory):

```bash
bash backup-images.sh
```

**Restore** (copy from local `backup-images/` to pod):

```bash
bash restore-images.sh
```

Both scripts automatically locate a running pod in the `general-services` namespace.

### Verify the deployment

```bash
kubectl -n general-services get pods
kubectl -n general-services get pvc
kubectl -n general-services get ingress
```

The application will be available at: `https://gs.iguzman.com.mx`
