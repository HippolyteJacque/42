using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.AI;
using UnityEngine.UI;

public class MayaMove : MonoBehaviour {

    [System.NonSerialized]public int Credits;
    [System.NonSerialized]public int Points;
	[System.NonSerialized]public float HP;
	[System.NonSerialized]public float maxHP;
	[System.NonSerialized]public float STR;
	[System.NonSerialized]public float AGI;
	[System.NonSerialized]public float CON;
	[System.NonSerialized]public float Armor;
	[System.NonSerialized]public float minDamage;
	[System.NonSerialized]public float maxDamage;
	[System.NonSerialized]public int Level;
	[System.NonSerialized]public float XP;
	[System.NonSerialized]public float xpNextLvl;
	[System.NonSerialized]public float money;

	public Slider HPbar;
	public Slider XPbar;
	public Text HPtext;
	public Text XPtext;
	public Text LVLtext;
	public Slider enemyHPbar;
	public Text enemyName;
	public Text enemyLvl;

    public GameObject lvlup;

	private float attackDistance = 2f;

    private bool alive;
	private float fireRate = 0.75f;
	private float nextFire;
	private GameObject target;
	private GameObject targetUI;
	public CanvasGroup EnemyCanvas;
    public CanvasGroup StatsCanvas;
    public CanvasGroup pointsBtn;
	Animator animator;

	NavMeshAgent agent;
	int layerMask = 1 << 2;

    void Start() {
        alive = true;
        Credits = 5;
        Points = 0;
    	STR = 20;
    	AGI = 20;
    	CON = 10;
    	Armor = 15;
    	minDamage = STR/2;
    	maxDamage = minDamage + 4;
		HP = 5 * CON;
		maxHP = HP;
		Level = 1;
		XP = 0;
		xpNextLvl = 100;
		money = 0;

    	layerMask = ~layerMask;
        agent = GetComponent<NavMeshAgent>();
        animator = GetComponent<Animator>();

        UImanager();
    }
    
    void Update() {
        if (alive == true){
            UpdateStats();
            UImanager();
            if (Input.GetMouseButtonUp(0)) {
                target = null;
                agent.enabled = true;
            }

            if (!agent.pathPending && agent.enabled == true){
                if (agent.remainingDistance <= agent.stoppingDistance){
                    if (!agent.hasPath || agent.velocity.sqrMagnitude == 0f){
                        animator.SetBool("isWalking", false);
                    }
                }
            }

            if (target){
                targetUI = target;
            }
            else {
                Ray ray;
                RaycastHit hit;
                ray = Camera.main.ScreenPointToRay(Input.mousePosition);
                if(Physics.Raycast(ray, out hit)){
                    if (hit.collider.tag == "enemy"){
                        targetUI = hit.transform.gameObject;
                    }
                    else {
                        targetUI = null;
                    }
                }
                else {
                    targetUI = null;
                }
            }
            if (HP <= 0){
                animator.SetBool("isWalking", false);
                animator.SetBool("isFighting", false);
                animator.SetBool("alive", false);
                StartCoroutine("death");
            }
        }
    }

    void FixedUpdate(){
        if (alive == true){
            move();
        }
    }

    void OnTriggerEnter(Collider col){
        if (col.tag == "LifeBall"){
            if (HP + maxHP * 0.3f > maxHP)
                HP = maxHP;
            else
                HP += maxHP * 0.3f;
            Destroy(col.gameObject);
        }
    }

    void UImanager(){
    	HPbar.value = HP/maxHP;
    	XPbar.value = XP/xpNextLvl;
    	if (HP >= 0)
    		HPtext.text = Mathf.RoundToInt(HP).ToString() + "/" + maxHP.ToString();
    	else
    		HPtext.text = "0/" + maxHP.ToString();
    	XPtext.text = Mathf.RoundToInt(XP).ToString() + "/" + xpNextLvl.ToString();
    	LVLtext.text = Level.ToString();

    	if (targetUI){
    		EnemyCanvas.alpha = 1f;
    		enemyHPbar.value = targetUI.GetComponent<ZombieMove>().HP/targetUI.GetComponent<ZombieMove>().maxHP;
			enemyName.text = targetUI.name.Replace("(Clone)", "");
			enemyLvl.text = targetUI.GetComponent<ZombieMove>().Level.ToString();
    	}
    	else {
    		EnemyCanvas.alpha = 0f;
    	}
        if (Points > 0)
            pointsBtn.alpha = 1f;
        else
            pointsBtn.alpha = 0f;
    }

    void UpdateStats(){
        minDamage = STR/2;
        maxDamage = minDamage + 4;
        maxHP = 5 * CON;
    }

    void move(){
    	if (Input.GetMouseButton(0)) {
            RaycastHit hit;
            Vector3 mouse = Camera.main.ScreenToViewportPoint(Input.mousePosition);
            if (Physics.Raycast(Camera.main.ScreenPointToRay(Input.mousePosition), out hit, 100, layerMask)) {

                if (StatsCanvas.alpha == 1f && mouse.x <= 0.5){
                    ;
                }
                else {
                    if (hit.collider.tag == "enemy" && Vector3.Distance(transform.position, hit.point) <= attackDistance && target == null){
                        target = hit.transform.gameObject;
                        animator.SetBool("isWalking", false);
                        attack(target);
                    }
                    else if (hit.collider.tag == "enemy" && Vector3.Distance(transform.position, hit.point) > attackDistance && target == null){
                        agent.destination = hit.point;
                        target = hit.collider.gameObject;
                        animator.SetBool("isWalking", true);
                    }
                    else if (target == null){
                        transform.LookAt(hit.point);
                        agent.destination = hit.point;
                        animator.SetBool("isFighting", false);
                        animator.SetBool("isWalking", true);
                    }
                }
            }
        }
        if (target){
        	transform.LookAt(target.transform.position);
        	if (Vector3.Distance(transform.position, target.transform.position) <= attackDistance){
        		animator.SetBool("isWalking", false);
        		attack(target);
        	}
        	else {
                agent.enabled = true;
        		animator.SetBool("isFighting", false);
        		animator.SetBool("isWalking", true);
        		agent.destination = target.transform.position;
        	}
        }
    }

    void attack(GameObject enemy){
        StopCoroutine(LvlUp());
    	agent.enabled = false;
    	if (Time.time > nextFire && enemy.GetComponent<ZombieMove>().HP > 0){
    		StopCoroutine("dmg");
			animator.SetBool("isFighting", true);
			nextFire = Time.time + fireRate;
			StartCoroutine(dmg(enemy));
		}
    	if (enemy.GetComponent<ZombieMove>().HP <= 0){
    		if (enemy.GetComponent<ZombieMove>().XP + XP >= xpNextLvl){
    			Level++;
                StartCoroutine(LvlUp());
                Points += 5;
                HP = maxHP;
    			XP = enemy.GetComponent<ZombieMove>().XP + XP - xpNextLvl;
    			xpNextLvl = xpNextLvl * 1.5f;
    		}
    		else {
    			XP = enemy.GetComponent<ZombieMove>().XP + XP;
    		}
    		money = money + enemy.GetComponent<ZombieMove>().money;
    		animator.SetBool("isFighting", false);
    		agent.enabled = true;
    		target = null;
    	}
    }

    IEnumerator dmg(GameObject enemy){
    	yield return new WaitForSeconds(0.5f);
    	
    	float hitChance = 75 + AGI - enemy.GetComponent<ZombieMove>().AGI;
    	if (Random.Range(0f, 100f) <= hitChance){
    		float dmg = Random.Range(minDamage, maxDamage);
    		dmg = dmg * (1 - enemy.GetComponent<ZombieMove>().Armor/200);
    		enemy.GetComponent<ZombieMove>().HP = enemy.GetComponent<ZombieMove>().HP - dmg;
    	}
    }

    IEnumerator LvlUp(){
        lvlup.SetActive(true);
        yield return new WaitForSeconds(5);
        lvlup.SetActive(false);
    }

    IEnumerator death(){
        alive = false;
        Credits--;
    	Destroy(GetComponent<CapsuleCollider>());
    	agent.enabled = false;
    	transform.Translate(-Vector3.up * Time.deltaTime/15);
    	yield return new WaitForSeconds(9);
    	//Destroy(gameObject);
    }

    public void addSTR(){
        STR += 1;
        Points--;
    }

    public void addAGI(){
        AGI += 1;
        Points--;
    }

    public void addCON(){
        CON += 1;
        Points--;
        HP += 5;
    }

}
