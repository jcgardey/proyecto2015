<?php

namespace StoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Alumno
 *
 * @ORM\Table(name="alumno")
 * @ORM\Entity(repositoryClass="StoreBundle\Repository\AlumnoRepository")
 */
class Alumno
{
    /**
     * @var int
     *
     * @ORM\Column(name="idAlumno", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @Assert\NotBlank(message="campo.vacio")
     * @Assert\Choice(choices={"DNI"}, message="tipoDocumento.invalido")
     * @ORM\Column(name="tipoDocumento", type="string", length=255)
     */
    private $tipoDocumento;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="campo.vacio")
     * @ORM\Column(name="numeroDocumento", type="string", length=255)
     */
    private $numeroDocumento;

    /**
     * @var string
     * @Assert\NotBlank(message="campo.vacio")
     * @Assert\Length(
     *      max=255,
     *      maxMessage= "campo.largo"
     * )
     * @ORM\Column(name="apellido", type="string", length=255)
     */
    private $apellido;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="campo.vacio")
     * @Assert\Length(
     *      max=255,
     *      maxMessage= "campo.largo"
     * )
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @var \DateTime
     * @Assert\NotNull(message="campo.vacio")
     * @Assert\Date(message="fecha.invalida")
     * @ORM\Column(name="fechaNacimiento", type="date")
     */
    private $fechaNacimiento;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="campo.vacio")
     * @Assert\Choice(choices={"Masculino", "Femenino"}, message="sexo.invalido")
     * @ORM\Column(name="sexo", type="string", length=255)
     */
    private $sexo;

    /**
     * @var string
     * @Assert\NotBlank(message="campo.vacio")
     * @Assert\Email(message="email.invalido")
     * @ORM\Column(name="mail", type="string", length=255)
     */
    private $mail;

    /**
     * @var string
     *
     * @ORM\Column(name="direccion", type="string", length=255)
     */
    private $direccion;

    /**
     * @var \DateTime
     * @Assert\NotNull(message="campo.vacio")
     * @Assert\Date(message="fecha.invalida")
     * @ORM\Column(name="fechaIngreso", type="datetime")
     */
    private $fechaIngreso;

    /**
     * @var \DateTime
     *
     * @Assert\Date(message="fecha.invalida")
     * @ORM\Column(name="fechaEgreso", type="datetime", nullable=true)
     */
    private $fechaEgreso;

    /**
     * @var \DateTime
     *
     * @Assert\Date(message="fecha.invalida")
     * @ORM\Column(name="fechaAlta", type="datetime")
     */
    private $fechaAlta;

    /**
    *
    * @Assert\NotBlank(message="direccion.vacia")
    * @ORM\Column(name="latitud", type="string")
    */
    private $latitud;

    /**
    * @Assert\NotBlank(message="direccion.vacia")
    * @ORM\Column(name="longitud", type="string")
    */
    private $longitud;

    /**
    * @ORM\OneToMany(targetEntity="Pago", mappedBy="alumno",cascade={"persist"})
    *
    **/
    private $pagos;

    /**
    * @Assert\NotNull(message="alumno.invalido")
    * @ORM\ManyToMany(targetEntity="Responsable", inversedBy="alumnos")
    * @ORM\JoinTable(name="alumno_responsable",
    *       joinColumns={ @ORM\JoinColumn(name="idAlumno", referencedColumnName="idAlumno") },
    *       inverseJoinColumns={ @ORM\JoinColumn(name="idResponsable", referencedColumnName="idResponsable") }
    * )
    **/
    private $responsables;


    /**
    * @ORM\Column(name="borrado", type="boolean")
    */
    private $borrado;


    /**
    * Un Alumno debe poseer al menos un responsable de gestión asignado
    * @Assert\IsTrue(message="alumno.invalido")
    */
    public function hasResponsable() {
        foreach ($this->getResponsables() as $responsable) {
            if ($responsable->getUsuario()->getRol() == 'ROLE_GESTION') {
                return true;
            }
        }
        return false;
    }


    /**
    * Se debe validar que un alumno no contega mas de un pago de la misma cuota
    * @Assert\IsTrue(message="pago.invalido")
    */
    public function hasPagosValidos() {
        foreach ($this->getPagos() as $pago) {
           if ($this->cantidadOcurrencias($pago->getCuota()) > 1 ) {
                return false;
           }
        }
        return true;
    }

    private function cantidadOcurrencias($cuota) {
        $cant = 0;
        foreach ($this->getPagos() as $pago) {
            if ($pago->getCuota()->getId() == $cuota->getId() ) {
                $cant++;
            }
        }
        return $cant;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set tipoDocumento
     *
     * @param string $tipoDocumento
     *
     * @return Alumno
     */
    public function setTipoDocumento($tipoDocumento)
    {
        $this->tipoDocumento = $tipoDocumento;

        return $this;
    }

    /**
     * Get tipoDocumento
     *
     * @return string
     */
    public function getTipoDocumento()
    {
        return $this->tipoDocumento;
    }

    /**
     * Set numeroDocumento
     *
     * @param string $numeroDocumento
     *
     * @return Alumno
     */
    public function setNumeroDocumento($numeroDocumento)
    {
        $this->numeroDocumento = $numeroDocumento;

        return $this;
    }

    /**
     * Get numeroDocumento
     *
     * @return string
     */
    public function getNumeroDocumento()
    {
        return $this->numeroDocumento;
    }

    /**
     * Set apellido
     *
     * @param string $apellido
     *
     * @return Alumno
     */
    public function setApellido($apellido)
    {
        $this->apellido = $apellido;

        return $this;
    }

    /**
     * Get apellido
     *
     * @return string
     */
    public function getApellido()
    {
        return $this->apellido;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Alumno
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set fechaNacimiento
     *
     * @param \DateTime $fechaNacimiento
     *
     * @return Alumno
     */
    public function setFechaNacimiento($fechaNacimiento)
    {
        $this->fechaNacimiento = $fechaNacimiento;

        return $this;
    }

    /**
     * Get fechaNacimiento
     *
     * @return \DateTime
     */
    public function getFechaNacimiento()
    {
        return $this->fechaNacimiento;
    }

    /**
     * Set sexo
     *
     * @param string $sexo
     *
     * @return Alumno
     */
    public function setSexo($sexo)
    {
        $this->sexo = $sexo;

        return $this;
    }

    /**
     * Get sexo
     *
     * @return string
     */
    public function getSexo()
    {
        return $this->sexo;
    }

    /**
     * Set mail
     *
     * @param string $mail
     *
     * @return Alumno
     */
    public function setMail($mail)
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * Get mail
     *
     * @return string
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * Set direccion
     *
     * @param string $direccion
     *
     * @return Alumno
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get direccion
     *
     * @return string
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set fechaIngreso
     *
     * @param \DateTime $fechaIngreso
     *
     * @return Alumno
     */
    public function setFechaIngreso($fechaIngreso)
    {
        $this->fechaIngreso = $fechaIngreso;

        return $this;
    }

    /**
     * Get fechaIngreso
     *
     * @return \DateTime
     */
    public function getFechaIngreso()
    {
        return $this->fechaIngreso;
    }

    /**
     * Set fechaEgreso
     *
     * @param \DateTime $fechaEgreso
     *
     * @return Alumno
     */
    public function setFechaEgreso($fechaEgreso)
    {
        $this->fechaEgreso = $fechaEgreso;

        return $this;
    }

    /**
     * Get fechaEgreso
     *
     * @return \DateTime
     */
    public function getFechaEgreso()
    {
        return $this->fechaEgreso;
    }

    /**
     * Set fechaAlta
     *
     * @param \DateTime $fechaAlta
     *
     * @return Alumno
     */
    public function setFechaAlta($f)
    {
        $this->fechaAlta =$f;

        return $this;
    }

    /**
     * Get fechaAlta
     *
     * @return \DateTime
     */
    public function getFechaAlta()
    {
        return $this->fechaAlta;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pagos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->responsables = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add pago
     *
     * @param \StoreBundle\Entity\Pago $pago
     *
     * @return Alumno
     */
    public function addPago(\StoreBundle\Entity\Pago $pago)
    {
        $pago->setAlumno($this);
        $this->pagos[] = $pago;
        return $this;
    }

    /**
     * Remove pago
     *
     * @param \StoreBundle\Entity\Pago $pago
     */
    public function removePago(\StoreBundle\Entity\Pago $pago)
    {
        $this->pagos->removeElement($pago);
    }

    /**
     * Get pagos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPagos()
    {
        return $this->pagos;
    }

    /**
     * Add responsable
     *
     * @param \StoreBundle\Entity\Responsable $responsable
     *
     * @return Alumno
     */
    public function addResponsable(\StoreBundle\Entity\Responsable $responsable)
    {
        $this->responsables[] = $responsable;

        return $this;
    }

    /**
     * Remove responsable
     *
     * @param \StoreBundle\Entity\Responsable $responsable
     */
    public function removeResponsable(\StoreBundle\Entity\Responsable $responsable)
    {
        $this->responsables->removeElement($responsable);
    }

    /**
     * Get responsables
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getResponsables()
    {
        return $this->responsables;
    }

    public function setLatitud ($l) {
        $this->latitud = $l;
    }

    public function getLatitud() {
        return $this->latitud;
    }

    public function setLongitud ($l) {
        $this->longitud = $l;
    }

    public function getLongitud() {
        return $this->longitud;
    }

    public function getBorrado() {
        return $this->borrado;
    }

    public function setBorrado($b) {
        $this->borrado = $b;
    }

    public function setResponsables($r) {
        $this->responsables = $r;
    }
}
